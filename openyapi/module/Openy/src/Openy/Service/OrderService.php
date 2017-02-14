<?php
/**
 * Service.
 * Order Service
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Billing\Orders
 * @category Orders
 * @see Openy\Module
 *
 */
namespace Openy\Service;

// OrderServiceInterface function arguments
use Openy\Model\Order\OrderEntity;

// Methods internal uses
use DomainException;
use \StdClass;
use Openy\Model\Order\OrderStatusEntity as OrderStatus;

// Constructor Arguments
use Openy\Interfaces\MapperInterface;
use Openy\V1\Rest\Preference\PreferenceEntity;
use Zend\Stdlib\AbstractOptions;

// Extends and Implements
use Openy\Service\AbstractService as ParentService;
use Openy\Interfaces\Service\OrderServiceInterface;
use Openy\Interfaces\Aware\OrderStatusServiceAwareInterface;
use Openy\Traits\Aware\OrderStatusServiceAwareTrait;
use Openy\Interfaces\Aware\PaymentServiceAwareInterface;
use Openy\Traits\Aware\PaymentServiceAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Openy\Interfaces\Openy\Payment\DefaultPaymentMethodInterface;
use Openy\Traits\Openy\Payment\DefaultPaymentMethodTrait;
use Openy\Interfaces\Openy\Log\LogInterface;
use Openy\Traits\Openy\Log\LogTrait;

/**
 * OrderService.
 * Implements OrderServiceInterface
 *
 * @uses Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
 * @uses Openy\Interfaces\Aware\OrderStatusServiceAwareInterface OrderStatusServiceAwareInterface
 * @uses Openy\Traits\Aware\OrderStatusServiceAwareTrait OrderStatusServiceAwareTrait
 * @uses Openy\Interfaces\Aware\PaymentServiceAwareInterface PaymentServiceAwareInterface
 * @uses Openy\Traits\Aware\PaymentServiceAwareTrait PaymentServiceAwareTrait
 * @see Zend\ServiceManager\ServiceLocatorAwareInterface Zend ServiceLocator Aware Interface
 * @see Zend\ServiceManager\ServiceLocatorAwareTrait Zend ServiceLocator Aware Trait
 * @uses Openy\Interfaces\Openy\Payment\DefaultPaymentMethodInterface DefaultPaymentMethodInterface
 * @uses Openy\Traits\Openy\Payment\DefaultPaymentMethodTrait DefaultPaymentMethodTrait
 * @uses Openy\Traits\LogTrait Log Trait
 * 
 */
class OrderService
	extends ParentService
	implements OrderServiceInterface,
			   OrderStatusServiceAwareInterface,
			   PaymentServiceAwareInterface,
			   ServiceLocatorAwareInterface,
			   DefaultPaymentMethodInterface,
			   LogInterface					
{
	use OrderStatusServiceAwareTrait, 
		PaymentServiceAwareTrait,
		ServiceLocatorAwareTrait,
		DefaultPaymentMethodTrait,
		LogTrait;

	
	
	/**
	 * Assist locating and producing orders
	 * @var MapperInterface
	 */	
	protected $orderMapper;	// aliases AbstractService::mapper property

	/**
	 * Used to perform persistent annotation of payments in database
	 * it produces receipts and invoices (depending on App configuration and billing User preferences)
	 * @var \Openy\Service\PaymentService
	 */
	protected $paymentService; //inherited in PaymentServiceAwareTrait

	/**
	 * Constructor.
	 * @param MapperInterface $orderMapper
	 * @param unknown $currentUser
	 * @param PreferenceEntity $userPrefs
	 * @param AbstractOptions $options
	 * @see \Openy\Service\AbstractService
	 */
	public function __construct(
			MapperInterface $orderMapper,
			$currentUser,
			PreferenceEntity $userPrefs,
			AbstractOptions $options
			)
	{
		parent::__construct($orderMapper,$currentUser,$userPrefs,$options);
		$this->orderMapper = &$this->mapper;
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
	 */
	public function locateOrder(OrderEntity $order){
		$result = clone $order;
		$result = $this->orderMapper->locate($result);
		return $result;
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
	 */
	public function prepareOrder(OrderEntity $order, $paymentUser = null){
		//TODO: PENDING DEVELOPMENT FOR PAYMENT USER
		// Order registry in Repo
		$order = $this->registerOrder($order);
		$order = $this->authorizeOrder($order);
		return $order;
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
	 */
	public function registerOrder(OrderEntity $order)
	{

		// Select the payment method for the order
		$order->paymentmethod = $order->paymentmethod ? : $this->getDefaultPaymentMethod();		
		$order->paymentmethodid = $order->paymentmethodid ? : $this->getDefaultPaymentMethodId();

		// TODO : Let the user ($userPrefs) say which is his preferred Payment method
		$order->orderstatus = OrderEntity::STATUS_ORDERED;
		$order->iduser = $order->iduser ? : $this->currentUser->getUser('iduser');

		if(null == $order->paymentmethodid)
		    throw new DomainException('Default payment method id not configured or does not exist (V1. Creditcard not set in preferences)', 500);

		$order = $this->orderMapper->insert($order);
		$order->orderstatus = $this->getOrderStatusService()->getStatus($order,FALSE);

		return $order;
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
	 */
	public function authorizeOrder(OrderEntity $order){
		// INITIAL CHECKS
		$located = $this->orderMapper->locate($order);
		$authorized = $this->getOrderStatusService()->canGetStatus($located,OrderEntity::STATUS_AUTHORIZED);

        $this->info('INFO: STARTING REFUEL ORDER AUTHORIZATION');
		// ACTIONS TO PERFORM DEPENDING ON PAYMENT METHOD
		$methods = $this->getOptions()->getPaymentMethods();
		switch ($order->paymentmethod):
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
				$authorized = $authorized ? $this->getPaymentService()->getOrderAuthorization($located) : FALSE;
				break;
			default:
				break;
		endswitch;

		if ($authorized):
            $this->info('INFO: GOT REFUEL ORDER AUTHORIZATION');

			$data = new StdClass();
		//TODO: Update this and mapper in order to manage order statuses
			$data->orderstatus = OrderEntity::STATUS_AUTHORIZED;
			$located = $this->orderMapper->update($located->idorder,$data);
			$orderstatus = $this->getOrderStatusService()->getStatus($located,FALSE);
		else:
			if ($located->idorder)
				$orderstatus = $this->getOrderStatusService()->getStatus($located);
			else
				$orderstatus = new OrderStatus(OrderStatus::STATUS_NOT_EXISTING);
		endif;

		if ($located->idorder){
			$located->orderstatus = $orderstatus;
	        $this->info('INFO: FINISHED REFUEL ORDER AUTHORIZATION '.($authorized ? '': 'WITH ERRORS'));
			if (!$authorized) $this->debug('ERRORS: Error at the end of refuel order authorization',["details" => "order not authorized with id ".$located->idorder]);
			return $located;
		}else{
			$order->orderstatus = $orderstatus;
	        $this->info('INFO: FINISHED REFUEL ORDER AUTHORIZATION WITH ERRORS');
            $this->debug('ERRORS: Error at the end of refuel order authorization',["details" => "order not found with id ".$order->idorder]);
			return $order;
		}


	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
	 */
	public function cancelOrder(OrderEntity $order){
		// INITIAL CHECKS
		$located = $this->orderMapper->locate($order);
		$cancelled = $this->getOrderStatusService()->canGetStatus($located,OrderEntity::STATUS_CANCELLED);
		// ACTIONS TO PERFORM DEPENDING ON PAYMENT METHOD
		$methods = $this->getOptions()->getPaymentMethods();
		switch ($located->paymentmethod):
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
				if (intval($located->orderstatus) == OrderEntity::STATUS_AUTHORIZED):
					$cancelled = $cancelled ? $this->getPaymentService()->cancelOrderAuthorization($located) : FALSE ;
				//TODO: Implement this if necessary
				endif;
				break;
			default:
				//TODO: Implement this
				break;
		endswitch;
		// PERSIST CHANGES
		if ($cancelled):
			$data = new StdClass();
			$data->orderstatus = OrderEntity::STATUS_CANCELLED;
			$located = $this->orderMapper->update($located->idorder,$data);
			$orderstatus = $this->getOrderStatusService()->getStatus($located,FALSE);
		else:
			if ($located->idorder)
				$orderstatus = $this->getOrderStatusService()->getStatus($located);
			else
				$orderstatus = new OrderStatus(OrderStatus::STATUS_NOT_EXISTING);
		endif;

		if ($located->idorder){
			$located->orderstatus = $orderstatus;
			return $located;
		}else{
			$order->orderstatus = $orderstatus;
			return $order;
		}
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
	 */	
	public function deliverOrder(OrderEntity $order){
		// INITIAL CHECKS
		$located = $this->orderMapper->locate($order);
		$delivered = $this->getOrderStatusService()->canGetStatus($located,OrderEntity::STATUS_ISSUED);
		// ACTIONS TO PERFORM DEPENDING ON PAYMENT METHOD
		$methods = $this->getOptions()->getPaymentMethods();
		switch ($located->paymentmethod):
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
				break;
			default:
				//TODO: Implement this if necessary
				break;
		endswitch;
		// PERSIST CHANGES
		if ($delivered):
			$data = new StdClass();
			$data->orderstatus = OrderEntity::STATUS_ISSUED;
			$data->amount = $order->amount;
			$data->summary = $order->summary;
			$data->deliverycode = $order->deliverycode;
			// The amount and the summary must be updated,
			// because them may change between ordering an delivery stages
			$located = $this->orderMapper->update($located->idorder,$data);
			$orderstatus = $this->getOrderStatusService()->getStatus($located,FALSE);
		else:
			if ($located->idorder)
				$orderstatus = $this->getOrderStatusService()->getStatus($located);
			else
				$orderstatus = new OrderStatus(OrderStatus::STATUS_NOT_EXISTING);
		endif;

		if ($located->idorder){
			$located->orderstatus = $orderstatus;
			return $located;
		}else{
			$order->orderstatus = $orderstatus;
			return $order;
		}
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
	 */	
	public function payOrder(OrderEntity $order){

		// INITIAL CHECKS
		$located = $this->orderMapper->locate($order);
		// ACTIONS TO PERFORM DEPENDING ON PAYMENT METHOD
		$payed = $this->getOrderStatusService()->canGetStatus($located,OrderEntity::STATUS_PAYED);

        $this->info('INFO: STARTING REFUEL ORDER PAYMENT');
		$methods = $this->getOptions()->getPaymentMethods();
				
		switch ($located->paymentmethod):
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
				$payment = $payed ? $this->getPaymentService()->getOrderPayment($located) : FALSE;
				break;
			default:
				//TODO: Implement this if necessary
				$located->paymentmethod = $order->paymentmethod;
				break;
		endswitch;
		// PERSIST CHANGES
		if ($payed && $payment->idpayment):
            $this->info('INFO: REFUEL ORDER PAYED THROUGH PAYMENT SERVICE');
			$data = new StdClass();
			$data->orderstatus = OrderEntity::STATUS_PAYED;
			$data->idpayment = $payment->idpayment;
			// The amount and the summary must be updated, because them may
			// change between ordering an delivery stages
			$located = $this->orderMapper->update($located->idorder,$data);
			$orderstatus = $this->getOrderStatusService()->getStatus($located,FALSE);
		else:
			if ($located->idorder)
				$orderstatus = $this->getOrderStatusService()->getStatus($located);
			else
				$orderstatus = new OrderStatus(OrderStatus::STATUS_NOT_EXISTING);
		endif;

		if ($located->idorder){
			$located->orderstatus = $orderstatus;
	        $this->info('INFO: FINISHED REFUEL ORDER PAYMENT '.($payed ? '': 'WITH ERRORS'));
			if (!$payed) $this->debug('ERRORS: Error at the end of refuel order payment',["details" => "order not payed with id ".$located->idorder]);
			return $located;
		}else{
			$order->orderstatus = $orderstatus;
	        $this->info('INFO: FINISHED REFUEL ORDER PAYMENT WITH ERRORS');
            $this->debug('ERRORS: Error at the end of refuel order payment',["details" => "order not found with id ".$order->idorder]);
			return $order;
		}
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
	 */	
	public function receiptOrder(OrderEntity $order){
		// INITIAL CHECKS
		$located = $this->orderMapper->locate($order);
		// ACTIONS TO PERFORM DEPENDING ON PAYMENT METHOD
		$payed = $this->getOrderStatusService()->canGetStatus($located,OrderEntity::STATUS_INVOICED);
		$result = $payed ? $this->getPaymentService()->getOrderReceipt($order) : $payed;
		// TODO : Build an error?
		return $result;
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
	 */
	public function invoiceOrder(OrderEntity $order){
		$receipt = $this->receiptOrder($order);
		return $receipt ? $this->getPaymentService()->getReceiptInvoice($receipt) : $receipt;
	}
}



