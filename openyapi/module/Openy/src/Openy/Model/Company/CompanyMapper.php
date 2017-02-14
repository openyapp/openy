<?php

namespace Openy\Model\Company;

use Openy\Model\AbstractMapper;
use Openy\Interfaces\BillingDataMapperInterface;
use Openy\Traits\BillingData\MapperTrait as BillingDataMapperTrait;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;
use Openy\Model\Company\CompanyEntity;


class CompanyMapper
	extends AbstractMapper
	implements BillingDataMapperInterface
{

    protected $tableName      = 'opy_company';
    protected $primary        = 'idcompany';
    protected $entity         = 'Openy\Model\Company\CompanyEntity';
    protected $collection     = 'Zend\Paginator\Paginator';
    protected $joinTableNames = ["billing_data" => ["bill"=>"opy_company_info"],
    							];

	use BillingDataMapperTrait;

	/**
	 * Gets a Company with populated attributes with its billing data
	 * @param  CompanyEntity $company The company to query the billing data
	 * @return \Openy\Model\Company\BillingDataCompanyEntity The company with billing data attributes populated
	 */
	public function getBillingDataCompany(CompanyEntity $company){
		$old_entity = $this->entity;
		$this->entity = 'Openy\Model\Company\BillingDataCompanyEntity';
		$result = $this->fetch($company->{$this->primary});
		$this->entity = $old_entity;
		return $result;
	}

	/**
	 * Gets a Billing data Entity compounded with Company billing data attributes
	 * @see function Openy\Model\Company\CompanyMapper\getBillingDataCompany
	 * @see trait Openy\Traits\BillingData\MapperTrait
	 * @param  CompanyEntity $company Company for what fetch for billing data
	 * @return \Openy\Model\Classes\BillingDataEntity
	 */
	public function getCompanyBillingData(CompanyEntity $company){
		$billingDataCompany = $this->getBillingDataCompany($company);
		return $this->getBillingData($billingDataCompany);
	}


    protected function fetchAllBuildQuery($filters){
        $select = parent::fetchAllBuildQuery($filters);
        // Alias for join tables;
        $billing_data = reset(array_keys($this->joinTableNames['billing_data']));

        if ($this->entity == 'Openy\Model\Company\BillingDataCompanyEntity'):
            $select ->join($this->joinTableNames['billing_data'],
                           $this->tableAliasName.'.'.$this->primary.' = '.$billing_data.'.idcompany',
                           array(
                                 'inv_name',
                                 'inv_document',
                                 'inv_mail',
                                 'inv_webpage',
                                 'inv_phone',
                                 'inv_address' => new \Zend\Db\Sql\Expression("CONCAT_WS(' ',inv_address,inv_postal_code,inv_locality,inv_country)"),
                                ),
                            $select::JOIN_INNER
                          );
            /*
            $logo = reset(array_keys($this->joinTableNames['logo']));
            $select->join($this->joinTableNames['logo'], $this->tableAliasName.'.idoffstation = '.$logo.'.idoffstation',['logoname'],$select::JOIN_INNER);
            */
        endif;
        return $select;
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
        $billing_data = reset(array_keys($this->joinTableNames['billing_data']));

		if ($this->entity == 'Openy\Model\Company\BillingDataCompanyEntity'):
			$select ->join($this->joinTableNames['billing_data'],
                           $this->tableAliasName.'.'.$this->primary.' = '.$billing_data.'.idcompany',
                           array(
                                 'inv_name',
                                 'inv_document',
                                 'inv_mail',
                                 'inv_webpage',
                                 'inv_phone',
                                 'inv_address' => new \Zend\Db\Sql\Expression("CONCAT_WS(' ',inv_address,inv_postal_code,inv_locality,inv_country)"),
                                ),
                            $select::JOIN_INNER
                          );
			/*
            $logo = reset(array_keys($this->joinTableNames['logo']));
			$select->join($this->joinTableNames['logo'], $this->tableAliasName.'.idoffstation = '.$logo.'.idoffstation',['logoname'],$select::JOIN_INNER);
            */
		endif;
		return $select;
	}

    protected function fetchGetHydratorInstance(){
    	$hydrator = parent::fetchGetHydratorInstance();
    	if ($this->entity == 'Openy\Model\Station\BillingDataCompanyEntity'):
    		$hydrator->setNamingStrategy(new MapperNamingStrategy(
    		                             [ 	'inv_name' 		=> 'billingName',
    		                             	'inv_document' 	=> 'billingId',
    		                             	'inv_address' 	=> 'billingAddress',
    		                             	'inv_webpage' 	=> 'billingWeb',
    		                             	'inv_mail'		=> 'billingMail',
    		                             	'inv_phone'		=> 'billingPhone',
    		                             	'logoname'		=> 'billingLogo',
    		                             ]
    		                             ));
    	endif;
    	return $hydrator;
    }



}