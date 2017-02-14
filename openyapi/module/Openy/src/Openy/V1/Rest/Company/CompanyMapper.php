<?php

namespace Openy\V1\Rest\Company;

use Openy\Model\AbstractMapper;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;

class CompanyMapper
	extends AbstractMapper
{
    protected $tableName      = 'opy_company';
    protected $primary        = 'idcompany';
    //protected $tableAliasName ;//= substr('tablename',0,3);
    protected $joinTableNames = ['terminals'=>['ot'=>'opy_tpv_terminal'],
                                 'merchants'=>['omcht'=>'opy_tpv_merchant']
                                ];
    protected $entity         = 'Openy\V1\Rest\Company\CompanyEntity';
    protected $collection     = 'Openy\V1\Rest\Company\CompanyCollection';

	protected function getHydratorInstance(){
	    $result = parent::getHydratorInstance();
	    $result->setNamingStrategy(new MapperNamingStrategy([
	                               	'billingName' => '',
	 								'billingAddress' => '',
	 								'billingId' => '',
									'billingWeb' => '',
									'billingLogo' => '',
									'billingMail' => '',
									'billingPhone' => '',
	                               ]));
	    return $result;
	}


	/**
     * @uses \Openy\Model\AbstractMapper::fetchAllBuildQuerySetFilters()
     */
    protected function fetchAllBuildQuerySetFilters($filters,&$query){

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

        return parent::fetchAllBuildQuerySetFilters($filters,$query);
    }


    /**
     * {@inheritDoc}
     * @uses \Openy\Model\AbstractMapper::fetch()
     */
    // TODO: Must return just a single occurrence,
    // but SQL returns multiple rows when a company has more than on terminal
    public function fetch($id,$where=[]){
        return parent::fetch($id,$where);
    }


    /**
     * {@inheritDoc}
     *
     * @uses   \Openy\Model\AbstractMapper::fetchBuildQuerySetWhere()
     */
    protected function fetchBuildQuerySetWhere($id,&$query,$where=[]){

        // Alias for terminals;
        $terminals = reset(array_keys($this->joinTableNames['terminals']));
        $merchants = reset(array_keys($this->joinTableNames['merchants']));

        // TODO: Filter by company user

        $query->join($this->joinTableNames['terminals'],
                     $this->tableAliasName.'.idcompany = '.$terminals.'.idcompany',
                        array('merchantcode'=>'merchantcode','terminal'=>'terminal','terminal_is_active'=>'active'),
                        $query::JOIN_LEFT)
              ->join($this->joinTableNames['merchants'],
                     $terminals.'.merchantcode = '. $merchants.'.merchantcode',
//                    .' AND '
//                    .$terminals.'.terminal = '. $merchants.'.terminal',
                        array('secret'=>'secret','registered'=>'registered'/*,['active'=>'merchant_is_active']*/),
                        $query::JOIN_LEFT)
              ->where('('.$terminals.'.active IS NOT NULL OR '.$terminals.'.terminal = '.$merchants.'.terminal)')
              ->where('IFNULL('.$merchants.'.active,0) > 0')
              ->order('terminal_is_active DESC, registered DESC')
              ->limit(1);
        return $query;
    }



}