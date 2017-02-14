<?php
/**
 * Factory.
 * Transaction Service Factory
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\POS
 * @category Transaction
 * @see Openy\Module
 *
 */
namespace Openy\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Openy\Service\TransactionService;

/**
 * TransactionServiceFactory.
 * Factorizes TransactionService instance(s)
 *
 * @uses Openy\Service\TransactionService TransactionService
 * @uses Openy\Interfaces\Service\TransactionServiceInterface TransactionServiceInterface
 *
 * @uses Zend\ServiceManager\FactoryInterface Zend Factory Interface
 * @uses Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface  
 */
class TransactionServiceFactory implements FactoryInterface
{
	/**
	 * (non-PHPdoc)
	 * @see \Zend\ServiceManager\FactoryInterface::createService()
	 * @return \Openy\Interfaces\Service\TransactionServiceInterface
	 */
    public function createService(ServiceLocatorInterface $sl)
    {
        $transactionMapper  = $sl->get('Openy\Model\Transaction\TransactionMapper');
        $currentUser        = $sl->get('Oauthreg\Service\CurrentUser');
        $userPrefs          = $sl->get('CurrentUserPreferences');
        $options            = $sl->get('Openy\Service\OpenyOptions');
		$tpvoptions			= $sl->get('Openy\Service\TpvOptions');
        $soapService        = $sl->get('Openy\Service\TPV\SOAP');
    	$creditcardService	= $sl->get('Openy\Service\CreditCard');
    	$stationService     = $sl->get('Openy\Service\OpyStation');    	 

        return new TransactionService($transactionMapper,$currentUser,$userPrefs,$options,$tpvoptions,$soapService,$creditcardService,$stationService);
    }
}