<?php
/**
 * Service.
 * Transaction Service
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\POS
 * @category Transaction
 * @see Openy\Module
 *
 */
namespace Openy\Service;

// TransactionServiceInterface function arguments 
use Openy\Model\Order\OrderEntity;
use Openy\Model\Payment\PaymentEntity;
use Openy\Model\Transaction\TransactionEntity;
use Openy\Model\Tpv\SOAP\ResponseEntity;

// Methods internal uses
use \StdClass;
use Openy\Model\Classes\MessageEntity;
use Openy\Model\Creditcard\CreditcardEntity;
use Openy\Model\Station\OpyStationEntity as Station;
use Openy\Model\Creditcard\CreditcardEntity as Creditcard;

// Constructor Arguments
use Openy\Interfaces\MapperInterface;
use Openy\V1\Rest\Preference\PreferenceEntity;
use Zend\Stdlib\AbstractOptions;
use Openy\Options\TpvOptions;
use Openy\Interfaces\Service\SoapServiceInterface;
use Openy\Interfaces\Service\CreditcardServiceInterface;
use Openy\Interfaces\Service\OpyStationServiceInterface;

// Extends and Implements 
use Openy\Service\AbstractService as ParentService;
use Openy\Interfaces\ServiceInterface;
use Openy\Interfaces\Service\TransactionServiceInterface;
use Openy\Interfaces\Properties\TpvOptionsInterface;
use Openy\Traits\Properties\TpvOptionsTrait;

/**
 * TransactionService.
 * Provides functions and methods implementing TransactionServiceInterface 
 *
 * @uses Openy\Interfaces\Service\TransactionServiceInterface TransactionServiceInterface
 * 
 * @uses Openy\Model\Order\OrderEntity Order Entity
 * @uses Openy\Model\Payment\PaymentEntity Payment Entity
 * @uses Openy\Model\Transaction\TransactionEntity Transaction Entity
 * @uses Openy\Model\Tpv\SOAP\ResponseEntity POS Transaction SOAP Response Entity
 * @uses Openy\Interfaces\Service\SoapServiceInterface SOAP Service Interface
 * @see  Openy\Service\SoapService SOAP Service 
 * @uses Openy\Model\Classes\MessageEntity Transaction Status Message Entity
 * @uses Openy\Interfaces\Service\CreditcardInterface Creditcard Service Interface
 * @see  Openy\Service\CreditcardService Creditcard Service
 * @uses Openy\Model\Creditcard\CreditcardEntity Creditcard Entity
 */
class TransactionService
	extends ParentService
	implements 	TransactionServiceInterface,
				TpvOptionsInterface,
				ServiceInterface
{
	use TpvOptionsTrait;
	/**
	 * Bank network SOAP Client service
	 * @var SOAPServiceInterface
	 */
	protected $soapService;
	
	/**
	 * Service for managing user credit cards
	 * @var CreditcardServiceInterface
	 */
	protected $creditcardService;
	
	/**
	 * 
	 * @var OpyStationServiceInterface
	 */
	protected $stationService;
	
	/**
	 * Transactions Mapper
	 * @var MapperInterface
	 */
	protected $transactionMapper;
	
	/**
	 * Constructor
	 * @param MapperInterface $transactionMapper
	 * @param mixed $currentUser
	 * @param PreferenceEntity $userPrefs
	 * @param AbstractOptions $options
	 * @param SOAPServiceInterface $soapService
	 * @param CreditcardServiceInterface $creditcardService
	 */
	public function __construct(
			MapperInterface $transactionMapper,
			$currentUser,
			PreferenceEntity $userPrefs,
			AbstractOptions $options,
			TpvOptions $tpvOptions,
			SoapServiceInterface $soapService,
			CreditcardServiceInterface $creditcardService,
			OpyStationServiceInterface $stationService
			)
	{
		parent::__construct($transactionMapper,$currentUser,$userPrefs,$options);
		$this->setTpvOptions($tpvOptions);
		$this->transactionMapper = &$this->mapper;
		$this->soapService = $soapService;
		$this->creditcardService = $creditcardService;
		$this->stationService = $stationService;
	}

	/**
	 * {@inheritDoc}
 	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface
	 */
	public function fetchTransaction(TransactionEntity $transaction){
		$transaction = $this->transactionMapper->locate($transaction);
		$request = $this->soapService->getRequest($transaction);
		if (!$this->soapService->hasValidResponse($request)){
			$transaction->lasterror = $this->soapService->getTransactionStatus($transaction);
		}
		return $transaction;
	}

	/**
	 * {@inheritDoc}
 	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface
	 */
	public function getTransactionStatus(TransactionEntity $transaction){
		if ($this->transactionMapper->exists($transaction,$fetch_if_exists = true)):
			$status = $this->soapService->getTransactionStatus($transaction);
		else:
			$status = new MessageEntity(null,'520');
			$status->text = 'Estado desconocido ("HTTP 520 Unknown Error")';
		endif;
		return $status;
	}

	/**
	 * {@inheritDoc}
 	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface
	 */
	public function updateTransactionStatus(TransactionEntity $transaction, ResponseEntity $response){
		$data = new \StdClass();
		$data->lastresponse = $response->response;
		$data->lastcode = $response->code;
		$data->authorizationcode = $response->authorizationcode;
		return $this->transactionMapper->update([ 'transactionid'=>$transaction->transactionid,
				                                  'transactiontype'=>$transaction->transactionType
				                                ],$data);
	}

	/**
	 * {@inheritDoc}
 	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface 
	 */
	public function getAuthorization(TransactionEntity &$transaction){
		//TODO: Re-though following condition, because it could be necessary to RETRY a transaction with temporary bad lastresponse
		if ($this->transactionMapper->exists($transaction))
			return false;
		$transaction->transactionType = TransactionEntity::TRANSACTION_TYPE_PREAUTH;

		$hasToken = (bool)$transaction->token;

		if (!$hasToken):
			$card = new CreditcardEntity();
			$card->idcreditcard = $transaction->idcreditcard;
			$transaction->token = $token = $this->creditcardService->getToken($card);
		endif;

		$transaction = $this->transactionMapper->insert($transaction);

		// TODO : Refactor: soap Service must return a SoapTransaction Instance
		$request = $this->soapService->getRequest($transaction);
//		$response = $this->soapService->getResponse($request);

		$soapData = ["lastresponse" 	=> $request->lastresponse,
		             "lastcode" 		=> $request->lastcode,
		             "authorizationcode"=> $request->authorizationcode,
		             "token" 			=> $token,
		             "secret" 			=> $transaction->secret,
		             ];
		$soapData = (object)$soapData;

		$transaction = $this->transactionMapper->update(["transactionid"	=>$transaction->transactionid,
														 "transactiontype" 	=>TransactionEntity::TRANSACTION_TYPE_PREAUTH
														 ],
														 $soapData);
		// Authorization leased
		$validResponse = $this->soapService->hasValidResponse($request);
		if ($validResponse && !$hasToken):
			$transaction->token = $request->token;
		endif; // valid response for given request

		return $validResponse;
	}

	/**
	 * {@inheritDoc}
 	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface 
	 */
	public function confirmAuthorization(TransactionEntity $transaction){
		$previousSibling = clone $transaction;
		$previousSibling -> transactionType = TransactionEntity::TRANSACTION_TYPE_PREAUTH;
		// A PREAUTH TRANSACTION WITH SAME transactionid MUST EXIST...
		if (! $this->transactionMapper->exists($previousSibling))
			return false;

		$transaction->transactionType = TransactionEntity::TRANSACTION_TYPE_CONFIRM_PREAUTH;
		// AND NO OTHER CANCEL TRANSACTION CAN EXISTS
		if ($this->transactionMapper->exists($transaction))
			return false;

		return $this->quickConfirmAuthorization($transaction);
	}
		/**
		 * Do not check for integrity.
		 * Just annotates in repo the transaction to be confirmed
		 * and performs a SOAP Request
		 * @param  TransactionEntity $transaction Authorized transaction to be confirmed
		 * @return boolean                        True if success or FALSE if something wrong
		 */
		protected function quickConfirmAuthorization(TransactionEntity $transaction){
			$transaction->transactionType = TransactionEntity::TRANSACTION_TYPE_CONFIRM_PREAUTH;

			$return = $this->transactionMapper->insert($transaction);
			$request = $this->soapService->getRequest($transaction);
			// Authorization leased
			$response = $this->soapService->getResponse($request);
			// TODO : Test in real scenario and check for lastresponse acquisition in error codes
			// In deed $response is tracking the DS_Response value
			$this->updateTransactionStatus($transaction,$response);
			return $this->soapService->hasValidResponse($request);
		}

	/**
	 * {@inheritDoc}
 	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface 
	 */
	public function cancellAuthorization(TransactionEntity $transaction){
		$previousSibling = clone $transaction;
		$previousSibling -> transactionType = TransactionEntity::TRANSACTION_TYPE_PREAUTH;
		// A PREAUTH TRANSACTION WITH SAME transactionid MUST EXIST...
		if (! $this->transactionMapper->exists($previousSibling))
			return false;

		$transaction->transactionType = TransactionEntity::TRANSACTION_TYPE_CANCEL_PREAUTH;
		// AND NO OTHER CANCEL TRANSACTION CAN EXISTS
		if ($this->transactionMapper->exists($transaction))
			return false;

		return $this->quickCancellAuthorization($transaction);
	}
		/**
		 * Do not check for integrity.
		 * Just annotates in repo the transaction to be cancelled
		 * and performs a SOAP Request
		 * @param  TransactionEntity $transaction Authorized transaction to be cancelled
		 * @return boolean                        True if success or FALSE if something wrong
		 */
		protected function quickCancellAuthorization(TransactionEntity $transaction){
			$transaction->transactionType = TransactionEntity::TRANSACTION_TYPE_CANCEL_PREAUTH;
			$transaction = $this->transactionMapper->insert($transaction);

			$request = $this->soapService->getRequest($transaction);
			// Authorization leased
			$response = $this->soapService->getResponse($request);
			// TODO : Test in real scenario and check for lastresponse acquisition in error codes
			// In deed $response is tracking the DS_Response value
			$this->updateTransactionStatus($transaction,$response);

			return $this->soapService->hasValidResponse($request);
		}

	/**
	 * {@inheritDoc}
 	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface 
	 */
	public function refundAuthorization(TransactionEntity $transaction){
		$previous = clone $transaction;
		$previous -> transactionType = TransactionEntity::TRANSACTION_TYPE_CONFIRM_PREAUTH;
		$CONFIRMED = $this->transactionMapper->exists($previous);
		$previous -> transactionType = TransactionEntity::TRANSACTION_TYPE_AUTH;
		$AUTHORIZED = $this->transactionMapper->exists($previous);

		// WARNING: OPENY CORE POLICY: JUST ONLY ONE REFUND WILL BE ADMITTED PER PAYMENT
		$previous -> transactionType = TransactionEntity::TRANSACTION_TYPE_REFUND_AUTH;
		$REFUNDED = $this->transactionMapper->exists($previous);

		if ( /*already*/$REFUNDED):
			return false;
		elseif ($CONFIRMED || $AUTHORIZED):
			return $this->quickRefundAuthorization($transaction);
		else:
			return false;
		endif;
	}

		/**
		 * Do not check for integrity.
		 * Just annotates in repo the transaction to be cancelled
		 * and performs a SOAP Request
		 * @param  TransactionEntity $transaction Authorized transaction to be cancelled
		 * @return boolean                        True if success or FALSE if something wrong
		 */
		protected function quickRefundAuthorization(TransactionEntity $transaction){
			$transaction->transactionType = TransactionEntity::TRANSACTION_TYPE_REFUND_AUTH;
			$transaction = $this->transactionMapper->insert($transaction);

			$request = $this->soapService->getRequest($transaction);
			// Authorization leased
			$response = $this->soapService->getResponse($request);
			// TODO : Test in real scenario and check for lastresponse acquisition in error codes
			// In deed $response is tracking the DS_Response value
			$this->updateTransactionStatus($transaction,$response);

			return $this->soapService->hasValidResponse($request);
		}

	/**
	 * {@inheritDoc}
 	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface 
	 */
	public function payAuthorization(TransactionEntity $transaction){
		$previous = clone $transaction;
		// TODO : REFACTOR THIS SERIOUSLY
		// ONLY ONE QUERY MUST BE PERFORMED IN DATABASE

		$previous->transactionType = TransactionEntity::TRANSACTION_TYPE_CANCEL_PREAUTH;
		$CANCELLED = $this->transactionMapper->exists($previous);

		$previous->transactionType = TransactionEntity::TRANSACTION_TYPE_CONFIRM_PREAUTH;
		$CONFIRMED = $this->transactionMapper->exists($previous);

		$previous->transactionType = TransactionEntity::TRANSACTION_TYPE_AUTH;
		$PAYED = $this->transactionMapper->exists($previous);

		$previous->transactionType = TransactionEntity::TRANSACTION_TYPE_PREAUTH;
		$PREAUTHORIZED = $this->transactionMapper->exists($previous);

		//TODO: RE-THOUGH THE FOLLOWING
		if ($PAYED || $CONFIRMED || $CANCELLED):
			return false;
		elseif ($PREAUTHORIZED && !$CANCELLED):
			// TODO: Optimitzar el locate, ja que hem consultat un exists de la mateixa transacció
			$previous = $this->transactionMapper->locate($previous);
			//$previous->transactionType = TransactionEntity::TRANSACTION_TYPE_PREAUTH;

			// IF AMOUNT REMAINS UNTOUCHED
			if((float)$previous->amount == (float)$transaction->amount):
				return $this->quickConfirmAuthorization($transaction);
			// IF AMOUNT HAS CHANGED..
			elseif ((float)$previous->amount > (float)$transaction->amount) :
				return $this->quickPayAuthorization($previous,$transaction);
			endif;
		else:
			return false;
		endif;
	}
		/**
		 * Do not check for integrity.
		 * Performs (annotating in repo) a confirmation of a previous preauth and optionally
		 * a refund operation (invoking SOAP engine for both)
		 * @param  TransactionEntity $transaction Authorized transaction to be cancelled
		 * @return boolean                        True if success or FALSE if something wrong
		 */
		protected function quickPayAuthorization(TransactionEntity $previous, TransactionEntity $transaction)
		{
				// we must CONFIRM previous auth transaction and later REFUND the difference
					// difference calculation
				$transaction->amount = (float)$previous->amount - (float)$transaction->amount;

					// confirmation for previous transaction
				$result = $this->quickConfirmAuthorization($previous);
				if ($result){
					$result = $this->quickRefundAuthorization($transaction);
				}
				else {
					// Annotate a refund transaction without  lastreponse code
					$transaction->transactionType = TransactionEntity::TRANSACTION_TYPE_REFUND_AUTH;
					$transaction = $this->transactionMapper->insert($transaction);
				}
			return $result;
		}


 	/**
	 * {@inheritDoc}
 	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface 
	 */
	public function getPaymentTransaction(PaymentEntity $payment){
		//TODO: Improve it, because jus returns the first occurrence found by transactionid
		return $this->transactionMapper->fetch($payment->transactionid);
	}

	/**
	 * {@inheritDoc}
 	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface 
	 */
	public function getOrderRelatedTransactions(OrderEntity $order){
		return $this->transactionMapper->fetchAll($params=array('transactionid'=>$order->idorder));
	}
	
	/**
	 * {@inheritDoc}
	 * @see  Openy\Interfaces\Service\TransactionServiceInterface Transaction Service Interface
	 */
	public function getTransactionFromOrder(OrderEntity $order, $fetch_from_repo = FALSE){
		$transactionid = $this->getTpvOptions()->getTransaction()->getPrefix('order') . $order->idorder;
		$transaction = new TransactionEntity($transactionid);
		$transaction->amount = $order->amount;
		
		if ($fetch_from_repo):
			$transaction = $this->fetchTransaction($transaction);
		else: //not fetching from repo, then populating with order properties
			if ($order->idopystation == Station::OPENY_STATION_ID):
				$transaction->merchantcode = $this->getTpvOptions()->getDefaults("merchant_code");
				$transaction->terminal     = $this->getTpvOptions()->getDefaults("terminal");
				$transaction->secret       = $this->getTpvOptions()->getDefaults("secret");
			else:
				$station = new Station();
				$station->idstation = $order->idopystation;
				// TODO check if class implements StationAwareInterface
				// TODO : Refactor this three-peat into a unique function call
				$transaction->merchantcode = $this->stationService->getMerchantCode($station);
				$transaction->terminal 	  = $this->stationService->getTerminal($station);
				$transaction->secret 	  = $this->stationService->getSecret($station);
			endif;
		
		// TODO : check if class implements CreditCardAwareInterface
		$card = new Creditcard();
		$card->idcreditcard = $this->getUserPrefs()->default_credit_card;
		$card->token = $this->creditcardService->getToken($card);
		$transaction->setCardData($card);
		endif;
		
		return $transaction;		
	}
	
}