<?php
/**
 * Interface.
 * Transaction Service Aware Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\POS
 * @category Transaction
 * @see Openy\Module
 *
 */
namespace Openy\Interfaces\Aware;

use Openy\Interfaces\Service\TransactionServiceInterface;

/**
 * TransactionServiceAwareInterface.
 * Defines getter and setter functions for a Transaction Service property
 *
 * @uses Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface
 * @see  \Openy\Service\TransactionService Transaction Service
 * @see \Zend\ServiceManger\ServiceLocatorAwareInterface Zend ServiceLocator Aware Interface
 */
interface TransactionServiceAwareInterface
{
	/**
	 * Sets transaction service property
	 * @param TransactionServiceInterface $TransactionService
	 * @return TransactionServiceAwareInterface Current Instance 
	 */
    public function setTransactionService(TransactionServiceInterface $TransactionService);

    /**
     * Gets transaction service property
     * @return TransactionServiceInterface
     */
    public function getTransactionService();
}