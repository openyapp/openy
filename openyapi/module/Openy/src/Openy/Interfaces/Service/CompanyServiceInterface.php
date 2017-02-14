<?php
/**
 * Interface.
 * Company Service Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Stations\Openy
 * @category Company
 * @see Openy\Module
 *
 */
namespace Openy\Interfaces\Service;

use Openy\Model\Station\OpyStationEntity;
use Openy\Interfaces\MapperInterface;


/**
 * CompanyServiceInterface.
 * Defines functions for a Service managing stations' companies
 *
 * @uses Openy\Model\Station\OpyStationEntity Openy Station Entity
 * @uses Openy\Interfaces\Classes\BillingDataInterface Billing Data Interface  
 * @uses Openy\Model\Company\CompanyEntity Company Entity
 * @see Openy\Service\OpyStationService Openy Station Service class
 * @see Openy\Service\CompanyService Openy Company Service 
 * 
 */
interface CompanyServiceInterface
{

	/**
	 * Constructor.
	 * @param MapperInterface $companyMapper
	 * @param MapperInterface $tpvCompanyMapper Mapper handling POS (TPV) information for company, such merchant code, secret... 
	 * @param MapperInterface $stationMapper
	 * @see \Openy\Model\Tpv\CompanyMapper tpvCompanyMapper class
	 */
	public function __construct(
			MapperInterface $companyMapper,
			MapperInterface $tpvCompanyMapper,
			MapperInterface $stationMapper
	);
	
	/**
	 * Gets the Company owning the station.
	 * @param OpyStationEntity $station
	 * @return \Openy\Model\Company\CompanyEntity
	 */
	public function getCompany(OpyStationEntity $station);
	
	/**
	 * Gets the Merchant Identifier Code.
	 * Each Station must operate through a bank POS assigned merchant code
	 * what belongs to Station (current) Company
	 *
	 * @param OpyStationEntity $station
	 * @return String
	 */
	public function getMerchantCode(OpyStationEntity $station);

	/**
	 * Gets the company default terminal.
	 * Each Station must operate through a bank POS assigned terminal
	 * what belongs to Station (current) Company and may be used once for each station
	 *
	 * @param OpyStationEntity $station
	 * @return Integer
	 */
	public function getTerminal(OpyStationEntity $station);

	/**
	 * Gets the Merchant Secret.
	 * Each Station must operate through a bank POS
	 * and has to authenticate its transactions by providing a secret bound to merchant code and terminal
	 * @see OpyStationServiceInterface::getMerchantCode getMerchantCode method
	 * @see OpyStationServiceInterface::getTerminal getTerminal method
	 * @see OpyStationServiceInterface::getSecret getSecret method on Openy Stations Service
	 * @param OpyStationEntity $station
	 */
	public function getSecret(OpyStationEntity $station);

	/**
	 * Gets Billing Data.
	 * Each Station must be capable to issue receipts
	 * after a bank POS operation (aka transaction),
	 * and some billing data must figure out on those receipts
	 * @see CompanyServiceInterface::getBillingData Company Billing data method
	 * @param OpyStationEntity $station
	 * @return \Openy\Interfaces\Classes\BillingDataInterface
	 */
	public function getBillingData(OpyStationEntity $station);

}