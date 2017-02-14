<?php
/**
 * Interface.
 * Openy Stations Service Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Stations\Openy
 * @category Stations
 * @see Openy\Module
 *
 */

namespace Openy\Interfaces\Service;

use Openy\Model\Station\OpyStationEntity;

/**
 * OpyStationServiceInterface.
 * Defines functions for a Service managing Openy Stations
 *
 * @uses Openy\Model\Station\OpyStationEntity Openy Station Entity
 * @see Openy\Service\OpyStationService Openy Station Service
 * @see Openy\Service\CompanyService Openy Company Service
 * @see Openy\Interfaces\Aware\CompanyServiceAwareInterface Company Service Aware Interface
 * 
 */
interface OpyStationServiceInterface
{
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
	 * Gets the terminal.
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
	 * @return \Openy\Interfaces\BillingData
	 */
	public function getBillingData(OpyStationEntity $station);
}