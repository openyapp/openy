<?php

namespace Openy\Model\Creditcard;

use Openy\Model\AbstractMapper;
use Openy\Interfaces\MapperInterface;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Zend\Stdlib\Hydrator\Strategy\SerializableStrategy;
use Zend\Serializer\Adapter\Json;


class ValidationAttemptMapper
	extends AbstractMapper
	implements MapperInterface
{

    protected $tableName      = 'opy_validation_attempt';
    protected $primary        = 'idcreditcard';
    //protected $tableAliasName ;//= substr('tablename',0,3);
    protected $entity         = 'Openy\Model\Creditcard\ValidationAttemptEntity';
    //protected $collection     ;//= 'Openy\Model\AbstractCollection';

    /**
     * Fields client, ip and ipv6 are collections persisted as JSON strings in Database
     * @return [type] [description]
     */
    protected function getHydratorInstance(){
        $hydrator = parent::getHydratorInstance();
        $hydrator->addStrategy('idcreditcard', new SerializableStrategy(new Json));
        $hydrator->addStrategy('accesses', new SerializableStrategy(new Json));
        return $hydrator;
    }


    /**
     * Instead of its father it fetches
     * @param  StdClass $data Object convertable to EntityClass
     * @return \Openy\Model\Creditcard\ValidationAttemptEntity
     */
    public function fetchByData($data){
        // Data is extracted and then an entity is hydrated
        if ($data instanceof \Openy\Model\Creditcard\ValidationAttemptEntity)
            $dataHydrator = $this->getHydratorInstance();
        else
            $dataHydrator = $this->getDataHydratorInstance();
        $newEntity = $this->fetchGetEntityInstance();

        $data = $dataHydrator->extract($data);

        $data['idcreditcard'] = (array)($data['idcreditcard'] ? : []);
        $result = $dataHydrator->hydrate($data,$newEntity);

        $dataHydrator->addFilter('UNIQUE CLAUSE FIELDS',function($property){
            return in_array($property,['pan','expires']);
        });

        // FetchBuildQuery function filters by user
        $query = $this->fetchBuildQuery(null);
        $query -> where($dataHydrator->extract($result));
        $query -> where->isNull('validated');

        $hydrator = $this->fetchGetHydratorInstance();
        $result = $this->fetchStatementExecute($query);

        $result = $result
                ? $hydrator->hydrate($result,$newEntity)
                : $newEntity;

        return $result;
    }


    protected function fetchBuildQuerySetWhere($id,&$query){
        $query = parent::fetchBuildQuerySetWhere($id,$query);
        $iduser = $this->currentUser->getUser('iduser');
        $query  ->where(['iduser'=> $iduser])
                ->order(['end'])
                ->limit(1);
        return $query;
    }

    public function insert($card){
        $entity = $this->insertGetEntityInstance();
        if ($card instanceof \Openy\Model\Creditcard\CreditcardEntity){
            // DATA must be a credit card.
            $entity->idcreditcard = [$card->idcreditcard];
            $entity->attempts = 1;
            $entity->iduser = $this->currentUser->getUser('iduser');
            $entity->pan = $card->pan;
            $entity->expires = $card->expires;
            return parent::insert($entity);
        }
        elseif ($card instanceof \Openy\Model\Creditcard\ValidationAttemptEntity){
            $entity = $card;
            $entity->attempts = 1;
            $entity->iduser = $this->currentUser->getUser('iduser');
            return parent::insert($entity);
        }
        return $entity;
    }

        protected function insertGetHydratorInstance(){
            $hydrator = parent::insertGetHydratorInstance();
            $hydrator->addStrategy('start', new CurrentTimestampStrategy('Y-m-d H:i:s'));
            $hydrator->addStrategy('end', new CurrentTimestampStrategy('Y-m-d H:i:s'));
            $hydrator->addFilter('fields_force_to_null',function($property){return !in_array($property,['validated','ipv6']);},FilterComposite::CONDITION_AND);
            return $hydrator;
        }


        protected function insertBuildSQLSetValues(&$data, &$insert){
            $hydrator = $this->insertGetHydratorInstance();
            $values = $hydrator->extract($data);
            if (!empty($values)){
                $insert->values($values);
                $data = $hydrator->hydrate($values,$data);
            }
            return $insert;
        }

    public function update($id, $data){
        if (!is_array($id)&&!is_object($id))
            $id = $data;
        return parent::update($id,$data);
    }

    protected function updateBuildSQL($id,$card){
        if (($id instanceof \Openy\Model\Creditcard\CreditcardEntity) ||
            ($id instanceof \Openy\Model\Creditcard\CreditCardDataEntity))
            $attempt = $this->fetchByData($id);
        else
            $attempt = $this->fetchByData($card);
        // $attempt->iduser = $this->currentUser->getUser('iduser');
        $attempt->end = NULL; //Will be automatically set to CURRENT_TIMESTAMP

        if ($card instanceof \Openy\Model\Creditcard\ValidationAttemptEntity){
            $attempt->idcreditcard = array_merge($attempt->idcreditcard,$card->idcreditcard);
            $attempt->accesses = array_merge($attempt->accesses,$card->accesses);
            $attempt->attempts = $card->attempts;
            $attempt->validated = $card->validated;
        }
        else{
            // There are only two kinds of update:
            // attempt data or validation data
            // FIRST USE CASE: Validation data (amount has matched)
            if (isset($card->validated) && ((bool)$card->validated)){
                $attempt->validated = $card->validated;
            }
            // SECOND USE CASE: Attempt data (amount hasn't matched)
            else{
                $attempt->attempts = isset($card->attempts) ? $card->attempts : $attempt->attempts +1;
                if (isset($card->idcreditcard))
                    $attempt->idcreditcard = array_merge($attempt->idcreditcard, (array)$card->idcreditcard);
                if (isset($card->accesses))
                    $attempt->accesses = array_merge($attempt->accesses,is_array($card->accesses)?$card->accesses:[$card->accesses]);
            }
        }
        $attempt = $this->getHydratorInstance()->extract($attempt);
        $attempt = (object) $attempt;

        $result = parent::updateBuildSQL($id,$attempt);
        return $result;
    }

       protected function updateBuildSQLSetWhere($id,&$update,$data=null){
            $where = [  'iduser' => $this->currentUser->getUser('iduser'),
                        'validated' => NULL,
                        'expires' => isset($id->expires) ? $id->expires : $data->expires,
                        'pan' => isset($id->pan) ? $id->pan : $data->pan,
                    ];
            $update->where($where);
            return $update;
        }


        protected function updateGetHydratorInstance($data){
            $hydrator = parent::updateGetHydratorInstance();
            $hydrator->addStrategy('end', new CurrentTimestampStrategy('Y-m-d H:i:s'));

            if (isset($data['validated']) && $data['validated'] === TRUE){
                $hydrator->addStrategy('validated', new CurrentTimestampStrategy('Y-m-d H:i:s'));
                $hydrator->addFilter('updatable_fields',function($property){return in_array($property,['end','validated']);},FilterComposite::CONDITION_AND);
            }
            else
                $hydrator->addFilter('updatable_fields',function($property){return in_array($property,['end','idcreditcard','attempts','accesses']);},FilterComposite::CONDITION_AND);

            if ($hydrator->hasFilter('fields_force_to_null'))
                $hydrator->removeFilter('fields_force_to_null');

            return $hydrator;
        }




}