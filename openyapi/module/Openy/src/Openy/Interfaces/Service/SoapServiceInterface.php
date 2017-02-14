<?php
/**
 * Interface.
 * Openy POS SOAP Client Service Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\POS\SOAP
 * @category SOAP
 * @see Openy\Module
 *
 */
namespace Openy\Interfaces\Service;

use Openy\Model\Transaction\TransactionEntity;
use Openy\Model\Tpv\SOAP\RequestEntity;
use Openy\Interfaces\MapperInterface;
use Zend\Stdlib\AbstractOptions;

/**
 * SoapServiceInterface.
 * Defines functions for a Service managing SOAP requests and responses
 * from client perspective
 *
 * @uses Openy\Model\Transaction\TransactionEntity POS Transaction Entity
 * @uses Openy\Model\Tpv\SOAP\RequestEntity SOAP Transaction Request Entity
 * @uses Openy\Model\Tpv\SOAP\ResponseEntity SOAP Transaction Response Entity
 * @uses Openy\Model\Classes\MessageEntity Transaction Status Message Entity
 * @see Openy\Service\SoapService Openy POS SOAP Client Service
 *
 */
interface SoapServiceInterface
{
	/**
	 * Creates an instance of SoapServiceInterface.
	 * @param MapperInterface $requestMapper
	 * @param MapperInterface $responseMapper
	 * @param MapperInterface $soapMapper
	 * @param AbstractOptions $options
	 * @param AbstractOptions $tpvoptions
	 */
	public function __construct(
			MapperInterface $requestMapper,
			MapperInterface $responseMapper,
			MapperInterface $soapMapper,
			AbstractOptions $options,
			AbstractOptions $tpvoptions);
	
	/**
	 * Gets a Request parting from a POS Transaction.
	 * If Transaction already had a request, returns that one
	 * @param TransactionEntity $transaction
	 * @return RequestEntity A request build up with given transaction
	 */
	public function getRequest(TransactionEntity $transaction);

	/**
	 * Gets the Response to a Request.
	 * Bank POS SOAP Server must answer incoming Requests with Responses 
	 * @param RequestEntity $request
	 * @return \Openy\Model\Tpv\SOAP\ResponseEntity Last registered response to given request
	 */
	public function getResponse(RequestEntity $request);

	/**
	 * Checks if the Request had a success Response
	 * @param RequestEntity $request
	 * @return bool TRUE if response means successful request, FALSE when error
	 */
	public function hasValidResponse(RequestEntity $request);

	/**
	 * Gets the status (error) for a (failed) transaction
	 * @param  TransactionEntity $transaction The transaction having a ResponseEntity with status to be queried
	 * @return \Openy\Model\Classes\MessageEntity Message containing status data
	 */
	public function getTransactionStatus(TransactionEntity $transaction);

}