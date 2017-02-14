<?php
namespace Openy\Model\Creditcard;

//use Openy\Model\AbstractMapper;
use Openy\V1\Rest\Creditcard\CreditcardMapper;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;

class ValidatedCardsMapper
	extends CreditcardMapper
   //extends AbstractMapper
{
	protected $tableName      = 'opy_validated_card';
//	protected $primary		  = 'token';
    protected $entity         = 'Openy\Model\Creditcard\ValidatedCreditcardEntity';




    public function fetch($id, $where=[]){
        $tableName = $this->tableName;
        $this->tableName = 'opy_credit_card';
        $result = parent::fetch($id);
        $this->tableName = $tableName;
        return $result;
    }

        protected function fetchGetHydratorInstance(){
            $hydrator = parent::fetchGetHydratorInstance();
            if ($hydrator->hasFilter('validcards_fields_not_exposed'))
                $hydrator->removeFilter('validcards_fields_not_exposed');
            return $hydrator;
        }


    /**
     * Inserts a new row in opy_validated_card table
     * @param  \StdClass $data Object containing data
     * @return ValidatedCreditcardEntity       The card being validated
     */
    public function insert($data){
    	$tableName = $this->tableName;
    	$this->tableName = 'opy_validated_card';
    	$result = parent::insert($data);
    	$this->tableName = $tableName;
    	return $result;
    }

        protected function insertGetEntityInstance(){
            $class = new \ReflectionClass('Openy\Model\Creditcard\ValidatedCreditcardEntity');
            $entity = $class->newInstance();
            return $entity;
        }


        protected function insertGetHydratorInstance(){
        	$hydrator = parent::insertGetHydratorInstance();
            if ($hydrator->hasFilter('data_not_provided_at_insert'))
                $hydrator->removeFilter('data_not_provided_at_insert');
        	$hydrator->addFilter('read_only_fields',function($property){return !in_array($property,[]);},FilterComposite::CONDITION_AND);
            $hydrator->addFilter('actual_fields',function($property){return in_array($property,['validator','token','transactionid','idcreditcard']);},FilterComposite::CONDITION_AND);
        	return $hydrator;
        }


	public function update($id, $data){
		if (isset($data->validated) && ($data->validated === TRUE)){
            //$data->validated = null; // Refer to updateGetHydratorInstance Strategy for 'validated'
            $result = parent::update($id,$data);

            if ($this->getOptions()->CreditCard->getPolicies('validation')->getAuto_Activate())
                $result->active = (FALSE !== $this->setActive($id,TRUE));
            return $result;
		}
        else return $this->fetch($id);
	}

        protected function updateGetHydratorInstance(){
            $hydrator = parent::insertGetHydratorInstance();
            // Overwrite of parent filters
            if ($hydrator->hasFilter('calculated_fields'))
                $hydrator->removeFilter('calculated_fields');
            // Overwrite parent filter
            // $hydrator->addFilter('calculated_fields', function($property){return !in_array($property,['active','favorite','expires','modified']);}, FilterComposite::CONDITION_AND);
            $hydrator->addFilter('updatable_fields',function($property){return in_array($property,['validated']);},FilterComposite::CONDITION_AND);
            $hydrator->addStrategy('validated', new CurrentTimestampStrategy('Y-m-d H:i:s'));
            return $hydrator;
        }


        public function delete($id){
            $card = $this->fetch($id);
            $this->tableName = 'opy_validated_card';
            if ($card->active)
                $result = $this->setActive($id,false);
            else
                $result = parent::delete($id);
            return (bool)$result;
        }
}

