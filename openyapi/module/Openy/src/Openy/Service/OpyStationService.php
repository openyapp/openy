<?php
/**
 * Service.
 * Openy Stations Service
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Stations\Openy
 * @category Stations
 * @see Openy\Module
 *
 */
namespace Openy\Service;

// OpyStationServiceInterface function arguments
use Openy\Model\Station\OpyStationEntity;

// Constructor Arguments
use Openy\Interfaces\Service\CompanyServiceInterface;
use Openy\Interfaces\MapperInterface;
use Zend\Stdlib\AbstractOptions;
use Openy\Traits\Properties\OptionsTrait;

// Extends and Implements
use Openy\Interfaces\Service\OpyStationServiceInterface;

/**
 * OpyStationService.
 * Implements the Openy Station Service Interface
 *
 * @uses Openy\Model\Station\OpyStationEntity Openy Station Entity
 * @uses Openy\Interface\Service\OpyStationServiceInterface Openy Station Service Interface
 * @see Openy\Service\CompanyService Openy Company Service
 * @see Openy\Interfaces\Aware\CompanyServiceAwareInterface Company Service Aware Interface
 *
 */
class OpyStationService
	implements OpyStationServiceInterface
{	
	use OptionsTrait;

	/**
	 * Openy Stations Mapper
	 * @var MapperInterface
	 */
	protected $stationMapper;
	
	/**
	 * Openy Company Service
	 * @var CompanyServiceInterface
	 */
	protected $companyService;

	/**
	 * Constructor.
	 *  
	 * @param CompanyServiceInterface $companyService
	 * @param MapperInterface $stationMapper
	 * @param AbstractOptions $options
	 */
	public function __construct(
			CompanyServiceInterface $companyService,
			MapperInterface $stationMapper,
			AbstractOptions $options
			)
	{
		$this->setOptions($options);
		$this->stationMapper = $stationMapper;
		$this->companyService = $companyService;
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\OpyStationServiceInterface
	 */
	public function getCompany(OpyStationEntity $station){
		//$idcompany = $station->idcompany;
/*		if (empty($idcompany)){
			$station = $this->stationMapper->locate($station);
		}*/
		return $this->companyService->getCompany($station);
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\OpyStationServiceInterface
	 */	
	public function getMerchantCode(OpyStationEntity $station){
		$station = $this->stationMapper->locate($station);
/*		$company = new CompanyEntity();
		$company->idcompany = $station->idcompany;*/
		return $this->companyService->getMerchantCode(/*$company,*/$station);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\OpyStationServiceInterface
	 */
	public function getTerminal(OpyStationEntity $station){
		$station = $this->stationMapper->locate($station);
		/*$company = new CompanyEntity();
		$company->idcompany = $station->idcompany;*/
		return $this->companyService->getTerminal(/*$company,*/$station);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\OpyStationServiceInterface
	 */
	public function getSecret(OpyStationEntity $station){
		$station = $this->stationMapper->locate($station);
		return $this->companyService->getSecret(/*$company,*/$station);
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\OpyStationServiceInterface
	 */
	public function getBillingData(OpyStationEntity $station){
		/*$company = new CompanyEntity();
		$company->idcompany = $station->idcompany;*/
		$billingData = $this->stationMapper->getStationBillingData($station);
		return $billingData;
		//$station = $this->stationMapper->locate($station);
		//return $this->companyService->getBillingData(/*$company,*/$station);*/
	}

}