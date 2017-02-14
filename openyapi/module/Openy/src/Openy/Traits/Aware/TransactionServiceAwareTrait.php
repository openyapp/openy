<?php
/**
 * Trait.
 * Transaction Service Aware Trait
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\POS
 * @category Transaction
 * @see Openy\Module
 *
 */
namespace Openy\Traits\Aware;

use Openy\Interfaces\Service\TransactionServiceInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * TransactionServiceAwareTrait.
 * Implements TransactionServiceAwareInterface
 *
 * @see \Openy\Interfaces\Aware\TransactionServiceAwareInterface TransactionServiceAwareInterface
 * @uses Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface
 * @uses Zend\ServiceManager\ServiceLocatorAwareInterface Zend ServiceLocator Aware Interface
 * @uses Zend\ServiceManager\ServiceLocatorInterface      Zend ServiceLocator Interface
 *
 */
trait TransactionServiceAwareTrait
{
	/**
	 * Transaction Service
	 * @var TransactionServiceInterface
	 * @see \Openy\Traits\Aware\TransactionServiceAwareTrait TransactionServiceAwareTrait
	 */
	protected $transactionService;

	/**
	 * Sets transaction service property
	 * @param TransactionServiceInterface $TransactionService
	 * @return TransactionServiceAwareInterface Current Instance
	 * @see \Openy\Traits\Aware\TransactionServiceAwareTrait TransactionServiceAwareTrait 
	 */
    public function setTransactionService(TransactionServiceInterface $transactionService){
        $this->transactionService = $transactionService;
        return $this;
    }

    /**
     * Gets transaction service property
     * @return TransactionServiceInterface
     * @see \Openy\Traits\Aware\TransactionServiceAwareTrait TransactionServiceAwareTrait 
     */
    public function getTransactionService(){
        if     (($this instanceof Zend\ServiceManager\ServiceLocatorAwareInterface)
            || (property_exists($this, "serviceLocator")
                && $this->serviceLocator instanceof ServiceLocatorInterface)
                )
        {
            $this->transactionService = $this->transactionService ? : $this->getServiceLocator()->get('Openy\Service\Transaction');
        }
        return $this->transactionService;
    }

}