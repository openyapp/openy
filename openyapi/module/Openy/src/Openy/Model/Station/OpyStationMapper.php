<?php

namespace Openy\Model\Station;

use Openy\Model\AbstractMapper;
use Openy\Interfaces\BillingDataMapperInterface;
use Openy\Traits\BillingData\MapperTrait as BillingDataMapperTrait;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;
use Openy\Model\Station\OpyStationEntity;


class OpyStationMapper
	extends AbstractMapper
	implements BillingDataMapperInterface
{

    protected $tableName      = 'opy_station';
    protected $primary        = 'idstation';
    protected $entity         = 'Openy\Model\Station\OpyStationEntity';
    protected $collection     = 'Zend\Paginator\Paginator';
    protected $joinTableNames = ["billing_data" => ["bill"=>"opy_station_info"],
    							 "logo" => ["logo"=>"off_station"],
    							];

	use BillingDataMapperTrait;

	/**
	 * Gets an Opy Station with populated attributes with its billing data
	 * @param  OpyStationEntity $station The station to query the billing data
	 * @return \Openy\Model\Station\BillingDataOpyStationEntity The station with billing data attributes populated
	 */
	public function getBillingDataStation(OpyStationEntity $station){
		$old_entity = $this->entity;
		$this->entity = 'Openy\Model\Station\BillingDataOpyStationEntity';
		$result = $this->fetch($station->{$this->primary});
		$this->entity = $old_entity;
		return $result;
	}

	/**
	 * Gets a Billing data Entity compounded with Openy Station billing data attributes
	 * @see function Openy\Model\Station\OpyStationMapper\getBillingDataStation
	 * @see trait Openy\Traits\BillingData\MapperTrait
	 * @param  OpyStationEntity $station [description]
	 * @return \Openy\Model\Classes\BillingDataEntity
	 */
	public function getStationBillingData(OpyStationEntity $station){
		$billingDataStation = $this->getBillingDataStation($station);
		return $this->getBillingData($billingDataStation);
	}

	/**
	 * {@inheritDoc}
	 * Fetches a BillingDataOpyStation if
	 * @param  Int $id Primary key value for $this->tableName
	 * @return Openy\Model\Station\OpyStationEntity
	 */
	protected function fetchBuildQuery($id){
		$select = parent::fetchBuildQuery($id);

		if ($this->entity == 'Openy\Model\Station\BillingDataOpyStationEntity'):
			$billing_data = reset(array_keys($this->joinTableNames['billing_data']));
			$select ->join($this->joinTableNames['billing_data'],
                           $this->tableAliasName.'.idstation = '.$billing_data.'.idopystation',
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
			$logo = reset(array_keys($this->joinTableNames['logo']));
			$select->join($this->joinTableNames['logo'], $this->tableAliasName.'.idoffstation = '.$logo.'.idoffstation',['logoname'],$select::JOIN_INNER);
		endif;
		return $select;
	}

    protected function fetchGetHydratorInstance(){
    	$hydrator = parent::fetchGetHydratorInstance();
    	if ($this->entity == 'Openy\Model\Station\BillingDataOpyStationEntity'):
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