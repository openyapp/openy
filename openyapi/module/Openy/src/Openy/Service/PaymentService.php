<?php
/**
 * Interface.
 * Payment Service
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Payment
 * @see Openy\Module
 *
 */
namespace Openy\Service;

// PaymentServiceInterface function arguments
use Openy\Model\Order\OrderEntity;
use Openy\Model\Order\OrderCollection;
use Openy\Model\Payment\PaymentEntity;
use Openy\Model\Payment\ReceiptEntity;
use Openy\Model\Payment\ReceiptCollection;
use Openy\V1\Rest\Invoice\InvoiceEntity;

// Methods internal uses
use \StdClass;
use Openy\Model\Classes\MessageEntity as Message;
use Openy\Interfaces\Classes\ErrorEntityInterface;
use Openy\Model\Payment\ErrorReceiptEntity;
use Openy\Model\Station\OpyStationEntity as StationEntity;


// Constructor Arguments
use Openy\Interfaces\Service\OrderServiceInterface;
use Openy\Interfaces\Service\ReceiptServiceInterface;
use Openy\Interfaces\Service\InvoiceServiceInterface;
use Openy\Interfaces\Service\OpyStationServiceInterface as StationServiceInterface;
use Openy\Interfaces\MapperInterface;
use Zend\Stdlib\AbstractOptions;

// Extends and Implements
use Openy\Service\AbstractService as ParentService;
use Openy\Interfaces\Service\PaymentServiceInterface;
use Openy\Interfaces\Aware\TransactionServiceAwareInterface;
use Openy\Traits\Aware\TransactionServiceAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Openy\Interfaces\Properties\TpvOptionsInterface;
use Openy\Traits\Properties\TpvOptionsTrait;
use Openy\Interfaces\Openy\Payment\DefaultPaymentMethodInterface;
use Openy\Traits\Openy\Payment\DefaultPaymentMethodTrait;
use Openy\Interfaces\Aware\PaymentOptionsServiceAwareInterface;
use Openy\Traits\Aware\PaymentOptionsServiceAwareTrait;

/**
* PaymentService.
* Provides functions and methods implementing PaymentServiceInterface
*
* @uses Openy\Interfaces\Service\PaymentServiceInterface PaymentServiceInterface
* @uses Openy\Model\Order\OrderEntity Order Entity
* @uses Openy\Model\Order\OrderCollection Orders Collection
* @uses Openy\Model\Payment\PaymentEntity Payment Entity
* @uses Openy\Model\Payment\ReceiptEntity Receipt Entity
* @uses Openy\Model\Payment\ReceiptCollection Receipts Collection
* @uses \Openy\V1\Rest\Invoice\InvoiceEntity Invoice Entity
* @uses Openy\Interfaces\Service\OrderServiceInterface Orders Service Interface
* @see \Openy\Service\OrderService Order Service class
* @uses Openy\Interfaces\Service\ReceiptServiceInterface Receipts Service Interface
* @see \Openy\Service\ReceiptService Receipts Service class
* @uses Openy\Interfaces\Service\InvoiceServiceInterface Invoices Service Interface
* @see \Openy\Service\InvoiceService Invoices Service class
* @uses Openy\Interfaces\Service\OpyStationServiceInterface Openy Stations Service Interface
* @see \Openy\Service\OpyStationService Openy Stations Service class 
* @uses \Zend\ServiceManager\ServiceLocatorAwareInterface Zend ServiceLocator Aware Interface
* @uses \Zend\ServiceManager\ServiceLocatorAwareTrait  Zend ServiceLocator Aware Trait
* @uses Openy\Interfaces\Openy\Payment\DefaultPaymentMethodInterface DefaultPaymentMethod Interface 
* @uses Openy\Traits\Openy\Payment\DefaultPaymentMethodTrait DefaultPaymentMethod Trait
*
*/
class PaymentService
	extends ParentService
	implements  PaymentServiceInterface,
				TransactionServiceAwareInterface,
				PaymentOptionsServiceAwareInterface,
				ServiceLocatorAwareInterface,
				TpvOptionsInterface,
				DefaultPaymentMethodInterface
{
	use TransactionServiceAwareTrait,
		PaymentOptionsServiceAwareTrait,
		ServiceLocatorAwareTrait,
		TpvOptionsTrait,
		DefaultPaymentMethodTrait;

	/**
	 * Payment Mapper
	 * @var MapperInterface
	 */
	protected $paymentMapper;	// aliases $mapper property

	
	/**
	 * Load Order locating capability to Payment Service
	 * @var OrderServiceInterface
	 */
	protected $orderService;

	/**
	 * Responsible of producing a persistent receipt for a given payment
	 * @var ReceiptServiceInterface
	 */
	protected $receiptService;

	/**
	 * Responsible of producing invoices from a collection of receipts
	 * @var InvoiceServiceInterface
	 */
	protected $invoiceService;

	/**
	 * Provides merchant information for station receiving a payment
	 * @var OpyStationServiceInterface
	 */
	protected $stationService;


	public function __construct(
			OrderServiceInterface $orderService,
			ReceiptServiceInterface $receiptService,
			InvoiceServiceInterface $invoiceService,
			StationServiceInterface $stationService,
			MapperInterface $paymentMapper,
			$currentUser,
			AbstractOptions $options,
			AbstractOptions $tpvoptions
			)
	{
		parent::__construct($paymentMapper,$currentUser,$userPrefs=null,$options);
		$this->paymentMapper = &$this->mapper;
		$this->setTpvOptions($tpvoptions);
		
		$this->orderService  = $orderService;
		$this->receiptService = $receiptService;
		$this->invoiceService = $invoiceService;
		$this->stationService = $stationService;		
	}


	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getOrderAuthorization(OrderEntity $order){
		$result = true;
		$methods = $this->getOptions()->getPaymentMethods();
		//TODO: Implement a treatment for $order->paymentmethod like the one done in getOrderPayment
		switch($order->paymentmethod):
			case $methods["paypal"]:
				//TODO: Implement this
				break;
			case $methods["voucher"]:
				//TODO: Implement this
				break;
			case $methods["credits"]:
				//TODO: Implement this
				break;
			case $methods["creditcard"]:
				$transaction = $this->getTransactionService()->getTransactionFromOrder($order);
				$result = $this->getTransactionService()->getAuthorization($transaction);
				break;
			default:
				//TODO: Implement this
				break;
		endswitch;
		return $result;
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function cancelOrderAuthorization(OrderEntity $order){
		$result = true;
		$methods = $this->getOptions()->getPaymentMethods();
		switch($order->paymentmethod):
			case $methods["paypal"]:
				//TODO: Implement this
				break;
			case $methods["voucher"]:
				//TODO: Implement this
				break;
			case $methods["credits"]:
				//TODO: Implement this
				break;
			case $methods["creditcard"]:
				$transaction = $this->getTransactionService()->getTransactionFromOrder($order);
				$result = $this->getTransactionService()->cancellAuthorization($transaction);
				break;
			default:
				//TODO: Implement this
				break;
		endswitch;
		return $result;
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getOrderPayment(OrderEntity $order){
		$payment = new PaymentEntity();
		$payment->idpayment = $order->idpayment;

		if (!$this->paymentMapper->exists($payment,$fetch_if_exists = true))
		    $payment = $this->newOrderPayment($order);
		return $payment;
	}

		/**
		 * Creates a payment from an Order
		 * @param  OrderEntity $order The order to be payed
		 * @return PaymentEntity      New payment registered for given $order
		 */
		protected function newOrderPayment(OrderEntity $order){
			$payment = new PaymentEntity();
			//$payment->amount = $order->amount;
			$payment->iduser = $order->iduser ? : $this->currentUser->get('iduser');
			$payment->idpaymentmethod = $order->paymentmethod ? : $this->getDefaultPaymentMethod();

			$payed = true;

			$methods = $this->getOptions()->getPaymentMethods();

			switch($payment->idpaymentmethod):
				case $methods["paypal"]:
					//TODO: Implement this
					break;
				case $methods["voucher"]:
					//TODO: Implement this
					break;
				case $methods["credits"]:
					//TODO: Implement this
					break;
				case $methods["creditcard"]:
					$transaction = $this->getTransactionService()->getTransactionFromOrder($order);
					$payed = $this->getTransactionService()->payAuthorization($transaction);
					break;
				default:
					//TODO: Implement this
					break;
			endswitch;

			if ($payed){
				$payment = $this->paymentMapper->insert($payment);
				$data = new StdClass();
				$data->transactionid = $transaction->transactionid;
				$payment = $this->paymentMapper->update($payment->idpayment,$data);
				if ($payment->idpayment && $this->getPaymentOptions()->getPolicies('payment')->getAuto_Receipt()){
					$receipt = $this->receiptFromOrder($order);
					// TODO: WHAT IF ORDER DELIVERYCODE ALREADY EXISTS AS RECEIPT OUTER NUMBER
					if (!$this->receiptService->receiptNumberExists($receipt)){
						$order->idpayment = $payment->idpayment;
						$receipt = $this->createOrderReceipt($order);
					}
				}
			}
			return $payment;
		}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\PaymentInterface;
	 */
	public function getPaymentOrder(PaymentEntity $payment){
		$order = new OrderEntity;
		$order->idpayment = $payment->idpayment;
		return $this->orderService->locateOrder($order);
	}

	/**
	 * Adds an error to an Entity, forcing this last to become its own descendant implementing ErrorEntityInterface
	 * @param StdClass &$entity  The entity to what error belongs to
	 * @param String $error_code A registered error code for entity type
	 */
	protected function addError(&$entity, $error_code){
		$errors = $this->getPaymentOptions()->getErrors();
		$errorInstance = ($entity instanceof ErrorEntityInterface);

		if ($entity instanceof PaymentEntity){
			$errors = $errors['payment'];
		}
		elseif ($entity instanceof OrderEntity){
			$errors = $errors['order'];
		}
		elseif ($entity instanceof ReceiptEntity){
			$errors = $errors['receipt'];
			$entity = ($errorInstance ? $entity : ErrorReceiptEntity::inherit($entity) );
		}
		$error = new Message;
		$error->code = $error_code;
		$error->translations = $errors[$error_code]->getTranslations()->toArray();
		$error->text 		 = $errors[$error_code]->getText();

		if ($entity instanceof ErrorEntityInterface){
			$entity->addError($error);
		}
		return $entity;
	}


	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getReceipt(PaymentEntity $payment, $receiptNumber = null, $idopystation = null){
		$receipt = new ReceiptEntity;
		$receipt->idpayment = $payment->idpayment;
		$receipt->receiptposid = $receiptNumber;
		$receipt->idopystation = $idopystation;
		$locatedReceipt = $this->receiptService->locateReceipt($receipt);
		// When an outer Receipt number is provied (identifying the station)
		// We must do several checks to avoid inner payment receipt duplication or outer receipt duplication
		if (!is_null($receipt->receiptposid) && !is_null($receipt->idopystation)){
			// If current payment has a persisted receipt
			if ($locatedReceipt->idpayment == $receipt->idpayment){
				// but persisted receipt doesn't match the provided outer Receipt number WE HAVE A MISMATCH
				if ($locatedReceipt->receiptposid != $receipt->receiptposid){
					// LET'S SOLVE THE MISMATCH:
					// First case: If PROVIDED outer receipt number is TEMPORARY,
					// seems SOMETHING WRONG has happened INNER the application, such "two receipt requests for same payment"
					if ($this->receiptService->hasTemporaryNumber($receipt)){
						$result = $this->addError($receipt,'WRONG_RECEIPT_NUMBER');
						return $result;
					}
					// Second case: PROVIDED outer receipt number is DEFINITIVE, BUT PERSISTED IS DEFINITIVE TOO,
					// seems SOMETHING STRANGE (nearly wrong) has happened OUTER the application, such "two receipts with same number have been issued"
					elseif (!$this->receiptService->hasTemporaryNumber($locatedReceipt)){
						$result = $this->addError($receipt,'RECEIPT_ALREADY_FOUND');
						return $result;
					}
					// Third case: PROVIDED outer receipt is DEFINITIVE and MUST BE PERSISTED
					// instead of current TEMPORARY one if NO OTHER RECEIPT WITH SAME NUMBER EXISTS
					else{
						//TODO : ARE WE SURE WE WANNA THIS "UNIQUE" BEHAVIOUR?
						$result = $locatedReceipt;
						// Check no other receipt exists with same number // that's why payment is set to null
						if (!$this->receiptService->receiptNumberExists($receipt)){
							$result = $this->addError($result,'NUMBER_REPEATED');
						}
						// No other receipt exists with same number
						else{
							$result ->receiptposid = $receiptNumber;
							$result = $this->receiptService->alterReceipt($result);
						}
						return $result;
					}
				}
			}
			// Current payment has not a receipt
			elseif (!is_null($payment->idpayment)){
				// But another receipt has been found with same outer number
				if ($locatedReceipt->receiptposid == $receipt->receiptposid){
					// If outer receipt number is TEMPORARY, must report that any receipt has been issued for that payment
					if ($this->receiptService->hasTemporaryNumber($receipt)){
						$result = $this->addError($receipt,'RECEIPT_NOT_FOUND');
						return $result;
					}
					// If outer receipt number is DEFINITIVE, must report a mismatch between payment and outer number
					else{
						$result = $this->addError($receipt,'WRONG_RECEIPT_NUMBER');
						return $result;
					}
				}
			}
		}
		return $locatedReceipt;
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getReceiptPayment(ReceiptEntity $receipt){
		return $this->paymentMapper->fetch($receipt->idpayment);
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getOrderReceipt(OrderEntity $order){
		$result = FALSE;
		if ($order->idpayment){
			$payment = $this->getOrderPayment($order);
			$receipt = $this->getReceipt($payment,$order->deliverycode,$order->idopystation);
			if ($receipt instanceof ErrorEntityInterface){
				$errors = $receipt->getErrors();
				$receipt = $this->receiptFromOrder($order);
				$receipt = ErrorReceiptEntity::inherit($receipt);
				$receipt->setErrors($errors);
			}
			else{
				if (is_null($receipt->receiptid))
					$receipt = $this->createOrderReceipt($order);
				else
					$receipt->billingdata = $this->getOrderBillingData($order);
			}
		}
		else
			$receipt = $this->receiptFromOrder($order);

		return $receipt;
	}

		protected function createOrderReceipt(OrderEntity $order){
			$receipt = $this->receiptFromOrder($order);
			$receipt = $this->receiptService->getReceipt($receipt);
			return $receipt;
		}

		protected function receiptFromOrder(OrderEntity $order){
			$receipt = new ReceiptEntity;
			$receipt->receiptposid = $order->deliverycode;
			$station = new StationEntity();
			$station->idstation = $order->idopystation;
			$receipt->idinvoicer = $this->stationService->getCompany($station)->idcompany;
			$receipt->idopystation = $order->idopystation;
			$receipt->billingdata = $this->getOrderBillingData($order);
			$receipt->taxes = $this->getOptions()->Payment->getTaxes()->toArray();
			//$receipt->template = $this->getOptions()->Receipt->getTemplate();
			$hydrator = new \Zend\Stdlib\Hydrator\Reflection;
			$receipt = $hydrator->hydrate($hydrator->extract($order),
			                              $receipt);
			return $receipt;
		}

		protected function getOrderBillingData(OrderEntity $order){
			$station = new StationEntity();
			$station->idstation = $order->idopystation;
			return $this->stationService->getBillingData($station);
		}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getReceiptOrder(ReceiptEntity $receipt){
		$payment = $this->getReceiptPayment($receipt);
		return $this->getPaymentOrder($payment);
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getReceiptsOrders(ReceiptCollection $receipts){
		$orders = array();
		foreach($receipts as $receipt):
			$order = $this->getReceiptOrder($receipt);
			$orders[$order->idorder]= $order;
		endforeach;
		return new OrderCollection($orders);
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getInvoice(ReceiptCollection $receipts){
		return $this->invoiceService->getInvoice($receipts);
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getReceiptInvoice(ReceiptEntity $receipt){
		$receipts = new ReceiptCollection([$receipt]);
		return $this->getInvoice($receipts);
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getOrderInvoice(OrderEntity $order){
		$receipt = $this->getOrderReceipt($order);
		return $this->getReceiptInvoice($receipt);
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
	 */
	public function getPaymentInvoice(PaymentEntity $payment){
		$receipt = $this->getPaymentReceipt($payment);
		return $this->getReceiptInvoice($receipt);
	}

}