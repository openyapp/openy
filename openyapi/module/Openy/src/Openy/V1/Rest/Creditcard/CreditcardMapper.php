<?php
namespace Openy\V1\Rest\Creditcard;

use Openy\Model\AbstractMapper;
use Openy\Model\Hydrator\Strategy\UnsignedIntZeroPadStrategy;
use Openy\Model\Hydrator\Strategy\PanStrategy;
use Openy\Model\Hydrator\Strategy\DatetimeFormatterStrategy;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Openy\Model\Hydrator\Strategy\UuidStrategy;
use Zend\Stdlib\Hydrator\Strategy\BooleanStrategy;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;

use Openy\Interfaces\Classes\CreditCardDataInterface;



use DomainException;
use InvalidArgumentException;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Expression as PredicateExpression;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\AbstractOptions;

use Zend\Stdlib\Hydrator\ObjectProperty;

use Openy\Interfaces\Aware\PreferenceAwareInterface;
use Openy\Traits\Aware\PreferenceAwareTrait;
use Openy\Traits\Aware\UserPrefsAwareTrait;
use Openy\Traits\Properties\UserPreferencesTrait;
use Openy\Interfaces\Properties\UserPreferencesInterface;

class CreditcardMapper
        extends AbstractMapper
        implements 	PreferenceAwareInterface,
        			UserPreferencesInterface
{
    use PreferenceAwareTrait;
    use UserPreferencesTrait;
 /*   protected $adapterMaster;
    protected $adapterSlave;
    protected $options;
    protected $currentUser;*/
    //TODO: Change main table from cards to validated cards, or switch them
    //      cause validated cards will be a table with no bad data, while cards will be a table with all user (bots, hackers) attempts
    protected $tableName      = 'opy_credit_card';
    protected $primary        = 'idcreditcard';
    protected $tableAliasName;
    protected $joinTableNames;
    protected $entity         = 'Openy\V1\Rest\Creditcard\CreditcardEntity';
    protected $collection     = 'Openy\V1\Rest\Creditcard\CreditcardCollection';
    protected $hydrator       = 'Openy\Model\Hydrator\CreditCardHydrator';
    protected $dataHydrator   = 'Zend\Stdlib\Hydrator\Reflection';
    protected $tpvoptions;

    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, $currentUser, $tpvoptions)
    {
        parent::__construct($adapterMaster, $adapterSlave, $options, $currentUser);
        $this->tpvoptions = $tpvoptions;
    }

    /**
     * {@inheritDoc}
     * @return [type] [description]
     */
    protected function getDataHydratorInstance($data = null){
        if (!is_null($data) && is_object($data) && !($data instanceof CreditCardDataEntity))
            $hydrator = new \Zend\Stdlib\Hydrator\ObjectProperty;
        else
            $hydrator = parent::getDataHydratorInstance();
        $hydrator->setNamingStrategy(new MapperNamingStrategy(
                                            ['cardexpmonth'  => 'month',
                                             'cardexpyear'   => 'year',
                                             'created'        => 'modified',
                                            ]));
        return $hydrator;
    }


    /**
     * Tells whenever a card is valid
     * @param  [type]  $id   [description]
     * @param  [type]  $user [description]
     * @return boolean       [description]
     */
    protected function isActive($id){
        $card = $this->fetch($id);
        $result = $card ? $card->active : false;
        return (bool) $result;
    }

    protected function isValidated($id){
        $card = $this->fetch($id);
        $result = $card ? $card->validated : false;
        return (bool) $result;
    }


    public function fetchAll($filters)
    {
        $this->joinTableNames = array('validcards'=>array('validcards'=>'opy_validated_card'),
                                      'activecards'=>array('activecards'=>'opy_active_card')
                                );
        // TODO: Need anything to be performed here?
        return parent::fetchAll($filters);
        // TODO: Need anything to be performed here?
    }


        protected function fetchAllBuildQuery($filters){
            $query = new Select();
            $query->from(array($this->tableAliasName => $this->tableName))
                  ->columns(array(
                            $query::SQL_STAR,
                            'expires'=> new \Zend\Db\Sql\Expression('CONCAT_WS(\'/\','.$this->tableAliasName.'.cardexpyear,'.$this->tableAliasName.'.cardexpmonth)'),
                            'favorite'  =>  new \Zend\Db\Sql\Expression($this->tableAliasName.'.idcreditcard LIKE \''.$this->getUserPrefs()->default_credit_card.'\''),
                            //TODO: Solve which one is favorite card, by checking user prefs
                            // @see Openy\Module at getServiceConfig() on entry "factories"->'Openy\Service\OpenyUserPrefs'
                            'modified' => new \Zend\Db\Sql\Expression('IFNULL('.$this->tableAliasName.'.updated,'.$this->tableAliasName.'.created)'),
                            )
                    );

            $this->fetchAllBuildQuerySetFilters($filters,$query);
            return $query;
        }


            /**
             * @uses \Openy\Model\AbstractMapper::fetchAllBuildQuerySetFilters()
             */
            protected function fetchAllBuildQuerySetFilters($filters,&$query){

                // Alias for validcards;
                $validcards = reset(array_keys($this->joinTableNames['validcards']));
                 // Alias for activecards;
                $activecards = reset(array_keys($this->joinTableNames['activecards']));

                /**
                 * User domain filtering
                */
                $user = $this->currentUser->getUser();

                $query->join($this->joinTableNames['validcards'],
                                $this->tableAliasName.'.idcreditcard = '.$validcards.'.idcreditcard',
                                array(
                                    'validated' => new \Zend\Db\Sql\Expression('IF(ISNULL('.$validcards.'.validated),0,1)'),
                                ),
                                $query::JOIN_INNER)
                      ->join($this->joinTableNames['activecards'],
                           $this->tableAliasName.'.idcreditcard = '.$activecards.'.idcreditcard',
                           array(
                                'active' => new \Zend\Db\Sql\Expression('IF(ISNULL('.$activecards.'.idcreditcard),0,1)'),
                           ),
                           $query::JOIN_LEFT
                          );

                $activeornotvalidated = new PredicateSet(
                                                    array(  new PredicateExpression($activecards.'.idcreditcard IS NOT NULL'),
                                                            new PredicateExpression('IFNULL('.$validcards.'.validated,0) = 0')),
                                                    PredicateSet::OP_OR);

                $query  ->where($activeornotvalidated)
                        ->where(array($validcards.'.validator'=>$user['iduser']))
                        ->order(array('favorite','updated DESC','cardexpyear DESC','cardexpmonth DESC'));

                //TODO: REMOVE DUMP
                //die($query->getSqlString());
            }

        protected function fetchAllGetHydratorInstance(){
            $hydrator = parent::fetchAllGetHydratorInstance();
            $hydrator->addStrategy('pan',new UnsignedIntZeroPadStrategy($str_length=4));
            //$hydrator->addStrategy('modified',new DatetimeFormatterStrategy($format='d/m/Y'));
            $hydrator->addStrategy('active', new BooleanStrategy("1","0"));
            $hydrator->addStrategy('favorite', new BooleanStrategy("1","0"));
            $hydrator->addStrategy('validated', new BooleanStrategy("1","0"));

            return $hydrator;
        }

    public function fetch($id,$where = []){
        $this->joinTableNames = array('validcards'=>array('validcards'=>'opy_validated_card'),
                                      'activecards'=>array('activecards'=>'opy_active_card')
                                );
        // TODO: Need anything to be performed here?
        return parent::fetch($id);
        // TODO: Need anything to be performed here?
    }

        protected function fetchBuildQuery($id){
            $query = new Select(array($this->tableAliasName => $this->tableName) );
            $query->columns(array(
                            $query::SQL_STAR,
                            'expires'=> new \Zend\Db\Sql\Expression('CONCAT_WS(\'/\','.$this->tableAliasName.'.cardexpyear,'.$this->tableAliasName.'.cardexpmonth)'),
                            'favorite'  =>  new \Zend\Db\Sql\Expression($this->tableAliasName.'.idcreditcard LIKE \''.$this->getUserPrefs()->default_credit_card.'\''),
                            //TODO: Solve which one is favorite card, by checking user prefs
                            // @see Openy\Module at getServiceConfig() on entry "factories"->'Openy\Service\OpenyUserPrefs'
                            'modified' => new \Zend\Db\Sql\Expression('IFNULL('.$this->tableAliasName.'.updated,'.$this->tableAliasName.'.created)'),
                            )
                    );

            // Alias for validcars;
            $validcards = reset(array_keys($this->joinTableNames['validcards']));
            $activecards = reset(array_keys($this->joinTableNames['activecards']));

            $query  ->join($this->joinTableNames['validcards'],
                           $this->tableAliasName.'.idcreditcard = '.$validcards.'.idcreditcard',
                           array(
                                'token',
                                'validator',
                                'transactionid',
                                'validated' => new \Zend\Db\Sql\Expression('IF(ISNULL('.$validcards.'.validated),0,1)'),
                           ),
                           $query::JOIN_INNER)
                    ->join($this->joinTableNames['activecards'],
                           $this->tableAliasName.'.idcreditcard = '.$activecards.'.idcreditcard',
                           array(
                                'active' => new \Zend\Db\Sql\Expression('IF(ISNULL('.$activecards.'.idcreditcard),0,1)'),
                           ),
                           $query::JOIN_LEFT
                          );
            $this->fetchBuildQuerySetWhere($id,$query);
            //TODO: REMOVE DUMP
            //die($query->getSqlString());
            return $query;
        }

            protected function fetchBuildQuerySetWhere($id,&$query){
                $user = $this->currentUser->getUser();
                // Alias for validcars;
                $validcards = reset(array_keys($this->joinTableNames['validcards']));
                $activecards = reset(array_keys($this->joinTableNames['activecards']));

                $query ->where(array(
                                    $this->tableAliasName.'.'.$this->primary => $id,
                                    $validcards.'.validator'=>$user['iduser'],
                                    //$activecards.'.iduser'=>$user['iduser'],
                                    )
                    );
                return $query;
            }

        protected function fetchGetHydratorInstance(){
            $hydrator = parent::fetchGetHydratorInstance();
            $hydrator->addStrategy('pan',new UnsignedIntZeroPadStrategy($str_length=4));
            //$hydrator->addStrategy('modified',new DatetimeFormatterStrategy($format='Y-m-d H:i:s'));
            $hydrator->addStrategy('active', new BooleanStrategy("1","0"));
            $hydrator->addStrategy('favorite', new BooleanStrategy("1","0"));
            $hydrator->addStrategy('validated', new BooleanStrategy("1","0"));
            // WARNING DO NOT CHANGE following label cause is used in ValidatecCardsMapper
            $hydrator->addFilter('validcards_fields_not_exposed',function($property){return !in_array($property,array('validator','token','transactionid')); });

            return $hydrator;
        }

    public function update($id, $data){
        $hydrator = new \Zend\Stdlib\Hydrator\ObjectProperty;
        $values = $hydrator->extract($data);
        foreach ($values as $field => $value)
        {
            switch($field):
                case "favorite":
                    if($this->isActive($id))
                    {
                        $this->setFavorite($id,$value);
                        unset($data->$field); //TODO : Translate $field to $data key value using hydrator
                    }                    
                break;
                case "active":
                    $this->setActive($id,$value);
                    unset($data->$field); //TODO : Translate $field to $data key value using Hydrator
                break;
                default:

                break;
            endswitch;
        }
        $data->updated = null;
        return parent::update($id,$data);
    }


    /**
     * Sets a card as favorite if is active
     * @param  [type] $id    [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    protected function setFavorite($id,$value){
        $value = intval((bool)$value);
        //TODO: Call user prefs and set this pref
        $this->getPreferences()->setPreference('default_credit_card', $id);
    }

    protected function setActive($id,$value){
        $user = $this->currentUser->getUser();
        $values = array('idcreditcard'  => (string)$id,
                        'iduser'        => $user['iduser']);

        $result = true;
        $value = (bool)$value;
        $sql = null;

        if ($this->isValidated($id)){
            if ($value){
                // Commented not (!) because this option ain't already agreed
                $select = new Select();
                $select ->from('opy_active_card')
                        ->columns(array(
                                        'idcreditcard'=>new Expression($this->adapterMaster->getPlatform()->quoteValue($values['idcreditcard'])),
                                        'iduser'=>new Expression($this->adapterMaster->getPlatform()->quoteValue($values['iduser']))
                                 ),
                                 false)
                        ->where($values)
                        ->having('COUNT(*) = 0');

                $insert = new Insert();
                $insert ->into('opy_active_card')
                        ->columns(array('idcreditcard','iduser'));
                      //->values($values); // not as useful as "select" method
                $insert->select($select);
                $sql = $insert;
                //TODO Remove DUMP
                //die($insert->getSqlString());
            }
            else{
            	$this->getPreferences()->setPreference('default_credit_card', null);
                $delete = new Delete();
                $delete ->from('opy_active_card')
                        ->where($values);
                $sql = $delete;
                //TODO: IT WOULD BE ELEGANT TO NOTIFY THAT USER DEFAULT CARD HAS BEEN DEACTIVATED
                //      IN THAT CASE
            }
            // EXECUTION (IF NEEDED)
            if ($sql){
                $statement = $this->adapterMaster->createStatement();
                $sql->prepareStatement($this->adapterMaster, $statement);
                $driverResult = $statement->execute();
                $result = $driverResult->getAffectedRows();
            }
            return $result;
        }
        else return false;
    }


        protected function updateGetHydratorInstance(){
            $hydrator = parent::updateGetHydratorInstance();

            $hydrator->addFilter('allowed_data_at_update', function($property){return in_array($property,['updated','transactionid','cardusername']);}, FilterComposite::CONDITION_AND);
            $hydrator->addFilter('calculated_fields', function($property){return !in_array($property,['active','favorite','expires','validated','modified']);}, FilterComposite::CONDITION_AND);
            $hydrator->addFilter('inherited_fields', function($property){return !in_array($property,['year','month']);}, FilterComposite::CONDITION_AND);

            $hydrator->addStrategy('updated', new CurrentTimestampStrategy('Y-m-d H:i:s'));
            return $hydrator;
        }

        /**
         *  Returns an instance of entity for being updated
         */
        protected function updateGetEntityInstance(){
            $class = new \ReflectionClass('\Openy\Model\Creditcard\CreditcardEntity');
            $entity = $class->newInstance();
            return $entity;
        }


        public function insert($data){
            $card = parent::insert($data);
            if ($data instanceof CreditCardDataInterface && !is_null($data->getPan()))
                $card->pan = $data->getPan();
            elseif (isset($data->pan))
                $card->pan = $data->pan;
            return $card;
        }

            protected function insertGetHydratorInstance(){
                $hydrator = parent::insertGetHydratorInstance();

                $hydrator->addFilter('data_not_provided_at_insert', function($property){return !in_array($property,['token','cvv','updated']);}, FilterComposite::CONDITION_AND);
                $hydrator->addFilter('calculated_fields', function($property){return !in_array($property,['active','favorite','expires','validated','modified']);}, FilterComposite::CONDITION_AND);
                $hydrator->addFilter('inherited_fields', function($property){return !in_array($property,['year','month']);}, FilterComposite::CONDITION_AND);

                $hydrator->addStrategy('idcreditcard',new UuidStrategy($this->options));
                $hydrator->addStrategy('pan',new PanStrategy());
                $hydrator->addStrategy('created', new CurrentTimestampStrategy('Y-m-d H:i:s'));
                $hydrator->addStrategy('cardexpyear',new UnsignedIntZeroPadStrategy($str_length=2));
                $hydrator->addStrategy('cardexpmonth',new UnsignedIntZeroPadStrategy($str_length=2));
                return $hydrator;
            }


            protected function insertGetEntityInstance(){
                $class = new \ReflectionClass('\Openy\Model\Creditcard\CreditcardEntity');
                $entity = $class->newInstance();
                return $entity;
            }



    /**
     * {@inheritDoc}
     *
     */
    public function deleteAll($filter = array()){
        return parent::deleteAll($filter);
    }

        /**
         * {@inheritDoc}
         *
         */
        protected function deleteAllBuildQuery($filter){
            $delete = parent::deleteAllBuildQuery($filter);
            $delete->from('opy_active_card');
            return $delete;
        }

            /**
             * {@inheritDoc}
             */
            protected function deleteAllBuildQuerySetWhere($filter,&$delete){
                $delete = parent::deleteAllBuildQuerySetWhere($filter,$delete);
                /**
                 * User domain filtering
                 */
                $user = $this->currentUser->getUser();
                $delete->where(array('iduser'=>$user['iduser']));
                return $delete;
            }
}
