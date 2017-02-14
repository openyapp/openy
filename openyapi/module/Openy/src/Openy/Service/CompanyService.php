<?php
/**
 * Service.
 * Company Service
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Stations\Openy
 * @category Company
 * @see Openy\Module
 *
 */
namespace Openy\Service;

// CompanyServiceInterface function arguments
use Openy\Model\Classes\BillingDataEntity;
use Openy\Model\Station\OpyStationEntity as Station;
use Openy\Model\Company\CompanyEntity;

// Constructor Arguments
use Openy\Interfaces\MapperInterface;

// Extends and Implements
use Openy\Interfaces\Service\CompanyServiceInterface;
use Openy\Interfaces\Properties\MapperInterface as MapperPropertyInterface;
use Openy\Traits\Properties\MapperTrait;

class CompanyService
	implements CompanyServiceInterface,
			   MapperPropertyInterface
{
	use MapperTrait;
	/**
	 * Company mapper
	 * @var MapperInterface
	 */
	protected $companyMapper;
	/**
	 * Mapper for Company with tpv fields but non billing data ones
	 * @var MapperInterface
	 */
	protected $tpvMapper;
	/**
	 * Mapper for Station entity retrieval
	 * @var MapperInterface
	 */
	protected $stationMapper;
	
	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\CompanyServiceInterface CompanyService Interface
	 *
	 */
	public function __construct(
			MapperInterface $companyMapper,
			MapperInterface $tpvCompanyMapper,
			MapperInterface $stationMapper
			)
	{
		$this->companyMapper = $companyMapper;
		$this->setMapper($this->companyMapper);
		$this->tpvMapper = $tpvCompanyMapper;
		$this->stationMapper = $stationMapper;
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\CompanyServiceInterface CompanyService Interface
	 */
	public function getMerchantCode(Station $station){
		$company = $this->tpvMapper->fetch($station->idcompany,['idstation'=>$station->idstation]);
		return $company->merchantcode;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\CompanyServiceInterface CompanyService Interface
	 */	
	public function getTerminal(Station $station){
		// TODO : Implement a new where condition against idstation
		$company = $this->tpvMapper->fetch($station->idcompany,['idstation'=>$station->idstation]);
		return $company->terminal;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\CompanyServiceInterface CompanyService Interface
	 */	
	public function getSecret(Station $station){
		$company = $this->tpvMapper->fetch($station->idcompany,['idstation'=>$station->idstation]);
		return $company->secret;
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\CompanyServiceInterface CompanyService Interface
	 */
	public function getBillingData(Station $station){
			$company = $this->companyMapper->fetch($station->idcompany,['idstation'=>$station->idstation]);
		return new BillingDataEntity($company);
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\CompanyServiceInterface CompanyService Interface
	 */	
	public function getCompany(Station $station){
		if (is_null($station->idcompany)){
			$station = $this->stationMapper->fetch($station->idstation);
		}
		$company = $this->companyMapper->fetch($station->idcompany);
		return $company;
	}

	/**
	 * //TODO: INCOMING IMPLEMENTATION
	 * @param CompanyEntity $company
	 */
	public function getInvoicer(CompanyEntity $company){

	}

}