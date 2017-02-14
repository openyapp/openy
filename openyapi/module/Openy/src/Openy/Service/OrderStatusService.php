<?php
/**
 * Service.
 * Order Status Service
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
use Openy\Model\Order\OrderStatusEntity as OrderStatus;

// Methods internal uses
use Openy\Model\Transaction\TransactionEntity;

// Constructor Arguments

// Extends and Implements
use Openy\Interfaces\Service\OrderStatusServiceInterface;
use Openy\Interfaces\Aware\OptionsServiceAwareInterface;
use Openy\Traits\Aware\OptionsServiceAwareTrait;
use Openy\Interfaces\Aware\PaymentServiceAwareInterface;
use Openy\Traits\Aware\PaymentServiceAwareTrait;
use Openy\Interfaces\Aware\PreferenceAwareInterface;
use Openy\Traits\Aware\PreferenceAwareTrait;
use Openy\Interfaces\Properties\UserPreferencesInterface;
use Openy\Traits\Properties\UserPreferencesTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * OrderService.
 * Implements OrderServiceInterface
 *
 * @uses Openy\Interfaces\Service\OrderStatusServiceInterface OrderStatusServiceInterface
 * @uses Openy\Model\Order\OrderEntity Order Entity
 * @uses Openy\Model\Order\OrderStatusEntity Order Status Entity
 * @uses Openy\Model\Transaction\TransactionEntity Transaction Entity
 * @uses Openy\Interfaces\Aware\OptionsServiceAwareInterface OptionsService Property AwareInterface
 * @uses Openy\Traits\Aware\OptionsServiceAwareTrait OptionsService Property AwareTrait
 * @uses Openy\Interfaces\Aware\PaymentServiceAwareInterface PaymentService Property AwareInterface
 * @uses Openy\Traits\Aware\PaymentServiceAwareTrait PaymentService Property AwareTrait
 * @uses Openy\Interfaces\Aware\PreferenceAwareInterface Preferences Property AwareInterface
 * @uses Openy\Traits\Aware\PreferenceAwareTrait Preferences Property AwareTrait
 * @uses Openy\Interfaces\Properties\UserPreferencesInterface User Prefs Property Interface
 * @see Zend\ServiceManager\ServiceLocatorAwareInterface Zend ServiceLocator Aware Interface
 * @see Zend\ServiceManager\ServiceLocatorAwareTrait Zend ServiceLocator Aware Trait
 *
 */
class OrderStatusService
	implements  OrderStatusServiceInterface,
				OptionsServiceAwareInterface,
				PaymentServiceAwareInterface,
				PreferenceAwareInterface,
				UserPreferencesInterface,
				ServiceLocatorAwareInterface

{

	use OptionsServiceAwareTrait, 
		PaymentServiceAwareTrait,
		PreferenceAwareTrait,
		UserPreferencesTrait,
		ServiceLocatorAwareTrait;


	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderStatusInterface
	 */
	public function canGetStatus(OrderEntity $order, $target){
		$current = (isset($order->orderstatus) && ($order->orderstatus instanceof OrderStatus))
				   ? $order->orderstatus->toInt()
				   : (int)$order->orderstatus;

		$valid_target = ($target === intval($target)
						&&
						in_array($target,
								 array( OrderEntity::STATUS_CANCELLED,
										OrderEntity::STATUS_ORDERED,
										OrderEntity::STATUS_AUTHORIZED,
										OrderEntity::STATUS_ISSUED,
										OrderEntity::STATUS_DELIVERED,
										OrderEntity::STATUS_PAYED,
										OrderEntity::STATUS_INVOICED,
						)));
		$current_equal_target = (intval($current)==intval($target));
		$current_preceed_target = (intval($current) == intval($target)-1);
		$current_is_revokable  = in_array($current,array(OrderEntity::STATUS_ORDERED,OrderEntity::STATUS_AUTHORIZED));
		$target_revoke_current = (intval($target)===OrderEntity::STATUS_CANCELLED);

		return  $valid_target && ($current_equal_target || $current_preceed_target || ($current_is_revokable && $target_revoke_current));
	}

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\Service\OrderStatusInterface
	 */
	public function getStatus(OrderEntity $order, $fetch_payment_status = TRUE){
		$transaction = null;
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
				if ($fetch_payment_status)
					$transaction = $this->getPaymentService()->getTransactionFromOrder($order, $fetch_from_repo = TRUE);
				break;
			default:
				break;
		endswitch;

		return $this->getOrderStatusFrom($order,$transaction);
	}


	protected function getOrderStatusFrom(OrderEntity $order, TransactionEntity $transaction = null)
	{ //TODO Replace in upcoming future Transaction with a parent class called PaymentMethodOperation
		if (isset($order->orderstatus) && ($order->orderstatus instanceof OrderStatus))
			return $order->orderstatus;

		$orderstatus = new OrderStatus();
		$orderstatus->status = (int)$order->orderstatus;
		$orderstatus->idorder = $order->idorder;
		if (!is_null($transaction)):
			$orderstatus->paymentoperationid = $transaction->transactionid;
			$orderstatus->lastresponse = $transaction->lastresponse;
			$orderstatus->lastcode = $transaction->lastcode;
			// default falls downto bank tpv message
			$orderstatus->codemsg = $transaction->lasterror->text("default");
			$lang = $this->getUserPrefs()->locale;
			$orderstatus->openymsg = $transaction->lasterror->text($lang ? :"default");
		endif;

		return $orderstatus;
	}



}