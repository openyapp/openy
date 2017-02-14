<?php

namespace Openy\Model\Tpv;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Stdlib\AbstractOptions;
use Openy\Model\Company\CompanyMapper as ParentMapper;

class CompanyMapper
	extends ParentMapper
{

    protected $tableName      = 'opy_company';
    protected $primary        = 'idcompany';
    protected $entity         = 'Openy\V1\Rest\Company\CompanyEntity';
    protected $collection     = 'Zend\Paginator\Paginator';
    //protected $collection     = 'Openy\V1\Rest\Company\CompanyCollection';

    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, $currentUser){
        parent::__construct($adapterMaster, $adapterSlave, $options, $currentUser);
        $this->joinTableNames['terminals']=['ot'=>'opy_tpv_terminal'];
        $this->joinTableNames['merchants']=['omcht'=>'opy_tpv_merchant'];
    }

    /**
     * Gets an hydrator having no naming strategy for billing data fields/attributes
     * @return  Zend\Stdlib\Hydrator\AbstractHydrator
     */
    protected function getHydratorInstance(){
        $hydrator = parent::getHydratorInstance();
        if ($hydrator->hasNamingStrategy())
            $hydrator->removeNamingStrategy();
        return $hydrator;
    }

    /**
     * @uses \Openy\Model\Company\CompanyMapper::fetchAllBuildQuery()
     */
    protected function fetchAllBuildQuery($filters){
        $query = parent::fetchAllBuildQuery($filters);
        die($query->getSqlString());

        // Alias for terminals;
        $terminals = reset(array_keys($this->joinTableNames['terminals']));
        $merchants = reset(array_keys($this->joinTableNames['merchants']));

        // TODO: Filter by company user

        $query->join($this->joinTableNames['terminals'],
                     $this->tableAliasName.'.idcompany = '.$terminals.'.idcompany',
                        array('merchantcode','terminal'),
                        $query::JOIN_INNER)
              ->join($this->joinTableNames['merchants'],
                     $terminals.'.merchantcode = '. $merchants.'.merchantcode',
//                    .' AND '
//                    .$terminals.'.terminal = '. $merchants.'.terminal',
                        array('secret','active'),
                        $query::JOIN_INNER)
              ->order(array('idcompany'));

        return $query;
    }


	/**
	 * {@inheritDoc}
	 * Fetches a BillingDataCompanyEntity if specified in $entity attribute
	 * @param  Int $id Primary key value for $this->tableName
	 * @return Openy\Model\Company\CompanyEntity
	 */
	protected function fetchBuildQuery($id){
		$select = parent::fetchBuildQuery($id);

        // Alias for join tables;
        $terminals = reset(array_keys($this->joinTableNames['terminals']));
        $merchants = reset(array_keys($this->joinTableNames['merchants']));

        $select->join($this->joinTableNames['terminals'],
                     $this->tableAliasName.'.idcompany = '.$terminals.'.idcompany',
                        array('merchantcode','terminal'),
                        $select::JOIN_INNER)
              ->join($this->joinTableNames['merchants'],
                     $terminals.'.merchantcode = '. $merchants.'.merchantcode',
//                    .' AND '
//                    .$terminals.'.terminal = '. $merchants.'.terminal',
                        array('secret','active'),
                        $select::JOIN_INNER)
              ->order(array('idcompany'));

//die($select->getSqlString());
		return $select;
	}
}