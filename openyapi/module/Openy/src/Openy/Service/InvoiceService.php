<?php
/**
 * Service.
 * Invoicing Service, what may be achieved as _Openy\Service\Invoice_
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Invoicing
 * @category Invoices
 * @see Openy\Module
 *
 */

namespace Openy\Service;

// CreditcardServiceInterface function arguments
use Openy\Model\Payment\ReceiptCollection;
use Openy\Model\Invoice\InvoiceEntity;

// Methods internal uses
use \StdClass;
use \DomainException;
use \Exception;
use Openy\Model\Order\OrderEntity;
use Openy\Model\Payment\ReceiptEntity;
use Openy\Model\Company\CompanyEntity;
use Openy\Model\Company\CompanyCollection;


// Constructor Arguments
use Openy\Interfaces\MapperInterface;
use Openy\Options\BillingOptions;
use Openy\V1\Rest\Preference\PreferenceEntity;

// Extends and Implements
use Openy\Service\AbstractService as ParentService;
use Openy\Interfaces\Service\InvoiceServiceInterface;
use Openy\Interfaces\Aware\PaymentServiceAwareInterface;
use Openy\Traits\Aware\PaymentServiceAwareTrait;
use Openy\Interfaces\Aware\CompanyServiceAwareInterface;
use Openy\Traits\Aware\CompanyServiceAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Openy\Interfaces\Openy\Billing\DefaultCompanyBillingDataInterface;
use Openy\Traits\Openy\Billing\DefaultCompanyBillingDataTrait;
use Openy\Interfaces\Aware\BillingOptionsServiceAwareInterface;
use Openy\Traits\Aware\BillingOptionsServiceAwareTrait;

/**
 * Invoicing Main Service.
 *
 * @uses  Openy\Interfaces\Service\InvoiceInterface Invoice Interface
 * @uses  Openy\Model\Payment\ReceiptMapper Receipt Mapper
 * @uses  Openy\Model\Invoice\InvoiceMapper Invoice Mapper
 * @uses  Openy\Interface\Aware\PaymentServiceAwareInterface Payment Service Aware Interface
 * @uses  Openy\Traits\Aware\PaymentServiceAwareTrait Payment Service Aware Trait
 * @see  \Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
 * @see  \Openy\Service\PaymentService Payment Service class
 * @uses  Openy\Interfaces\Aware\CompanyServiceAwareInterface CompanyService Aware Interface
 * @uses  Openy\Interfaces\Aware\CompanyServiceAwareTrait Company Service Aware Trait
 * @see  \Openy\Interfaces\Service\CompanyServiceInterface Company Service Interface
 * @see  \Openy\Service\CompanyService Company Service Class
 *
 */
class InvoiceService
	extends ParentService
	implements InvoiceServiceInterface,
			   PaymentServiceAwareInterface,
			   CompanyServiceAwareInterface,
			   ServiceLocatorAwareInterface,
			   DefaultCompanyBillingDataInterface,
			   BillingOptionsServiceAwareInterface
{

	use PaymentServiceAwareTrait,
	    CompanyServiceAwareTrait,	    
	    ServiceLocatorAwareTrait,
	    DefaultCompanyBillingDataTrait,
	    BillingOptionsServiceAwareTrait;

	/**
	 * Mapper used to persist and retrieve invoices
	 * @var MapperInterface
	 * @ignore
	 */
	protected $invoiceMapper;


	/**
	 * Responsible of producing a persistent receipt for a given payment
	 * @var MapperInterface
	 * @ignore
	 */
	protected $receiptMapper;


	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\InvoiceServiceInterface Invoice Service Interface
	 */
	public function __construct(
			MapperInterface $receiptMapper,
			MapperInterface $invoiceMapper,
			$currentUser,
			PreferenceEntity $userPrefs,
			BillingOptions $billingOptions
			)
	{
		parent::__construct($invoiceMapper,$currentUser,$userPrefs,$billingOptions);
		$this->invoiceMapper  = &$this->mapper;
		$this->receiptMapper  = $receiptMapper;
	}


	/**
	 *
	 * {@inheritDoc}
	 *
	 * <h3>Example:</h3>
	 * <code>
	 * $receipts = array_map(function($id){return new ReceiptEntity($id);},[1,2,3,4]);
	 * $collection = new ReceiptCollection($receipts);
     * return $invoiceService->getInvoice($collection);
     *
	 * </code>
	 *
	 * @see  \Openy\Interfaces\Service\InvoiceServiceInterface InvoiceInterface
	 *
	 */
	public function getInvoice(ReceiptCollection $receipts, $date = null, $iduser = null){
		$persistentReceipts = $this->receiptMapper->fetchAll($receipts->getEntitiesIds());
		
		if (!(count($persistentReceipts) == count($receipts))){		
			throw new \DomainException('Cannot invoice given receipts. Some of them were not found', \Zend\Http\Response::STATUS_CODE_412);
			return FALSE;
		}		
		
		$invoices = $persistentReceipts->getEntitiesProperty('idinvoice');
		if (count(array_diff($invoices,[NULL])) >0){
			if (count($invoices) == 1){
				return $this->invoiceMapper->fetch(reset($invoices));
			}
			else{
				throw new \DomainException('Cannot invoice given receipts. Some of them have been invoiced previously', \Zend\Http\Response::STATUS_CODE_412);
				return FALSE;
			}
		}

		$iduser = $iduser ? (string)$iduser : $this->currentUser->get('iduser');
		// TODO: Improve this in order to set current user as the given one
		if (!$this->userPrefs->isComplete()){
			throw new \DomainException('Cannot invoice this user. Billing preferences are not complete enough.', \Zend\Http\Response::STATUS_CODE_412);
			return FALSE;
		}
		$date   = $date ? (string)$date : date('Y-m-d');

		$result = true;
		if ($result):
			$idinvoicer =  null;
			$billingData = $this->getBillingData($persistentReceipts,$idinvoicer);

			$this->invoiceMapper->setBillingData($billingData);
			$this->invoiceMapper->setIdinvoicer($idinvoicer);

			$result = $this->invoiceMapper->insert($persistentReceipts,$date,$iduser);
			//TODO añadir el prefijo en el resultado de invoicenumber
			if ($result->idinvoice)
			{
				try{
					$receipts = $receipts->getEntitiesIds();
					// receipts invoice reference must be updated
					$data = new \StdClass();
					$data->idinvoice = $result->idinvoice;

					$collection = $this->receiptMapper->update($receipts,$data);
					try{
						$orders = $this->getPaymentService()->getReceiptsOrders($collection);
						$orderMapper = $this->getPaymentService()->getOrderService()->getMapper();
						unset($data->idinvoice);
						$data->orderstatus = OrderEntity::STATUS_INVOICED;
						$orders = $orders->getEntitiesIds();
						$orders = $orderMapper->update($orders,$data);
					}catch(\Exception $e){
						$this->receiptMapper->update($receipts,['idinvoice'=>null]);
						throw $e;
					}
				}catch(\Exception $e){
					$this->invoiceMapper->delete($result->idinvoice);
					throw $e;
				}
			}

		endif;

		return $result;
	}
		/**
		 * Determines the billing data what applies to a collection of receipts,
		 * depending on billing policy configured for invoices
		 * When not in POLICY_BILLING_LOCAL_DATA, and not all receipts belong to same stations company,
		 * and empty BillingDataEntity is returned
		 * @param  ReceiptCollection $receipts [description]
		 * @return \Openy\Model\Classes\BillingDataEntity Billing data what applies for the receipt collection invoice
		 */
		protected function getBillingData(ReceiptCollection $receipts, &$invoicer=null){
			$policy = $this->getOptions()['invoices']->getPolicy();
			switch($policy):
				case POLICY_BILLING_LOCAL_DATA:
					$idinvoicer = $this->getDefaultCompanyId('invoices');
					return $this->getDefaultCompanyBillingData('invoices');
				break;

				case POLICY_BILLING_DB_DATA_IF_AVAILABLE:
				case POLICY_BILLING_ALLWAYS_DB_DATA:
					$companies = $this->getCompanies($receipts, $as_collection = FALSE);
					if (count($companies) != 1)
						throw new \DomainException('Cannot invoice given receipts. Have more than one issuer candidate company or no candidate at all', \Zend\Http\Response::STATUS_CODE_412);
					else{
						$company = array_pop($companies);
						$idinvoicer = $company->idcompany;
						$billingData = $this->getCompanyService()->getMapper()->getCompanyBillingData($company);
					}

					if (!$billingData->isComplete()):
						if ($policy == POLICY_BILLING_DB_DATA_IF_AVAILABLE){
							$idinvoicer = $this->getDefaultCompanyId('invoices');
							return $this->getDefaultCompanyBillingData('invoices');
						}
						else
							throw new \DomainException('Cannot invoice given receipts. Issuer company billing data is incomplete', \Zend\Http\Response::STATUS_CODE_412);
					else:
						return $billingData;
					endif;
				break;
			endswitch;
			return NULL;
		}

			/**
			 * Gets all companies behind a collection of receipts
			 * and returns them in a collection (without fetching their data from DB)
			 * @param  ReceiptCollection $receipts Collection of receipts to be searched for companies
			 * @param  Boolean           $as_collection Switches the return tye between CompanyCollection or a simple array
			 * @return CompanyCollection|array  Companies collected from receipts
			 */
			protected function getCompanies(ReceiptCollection $receipts,$as_collection = FALSE){
				$companies = array();
				foreach($receipts as $receipt){
					if (($receipt instanceof ReceiptEntity)
						&& (!isset($companies[$receipt->idinvoicer])))
						$companies[$receipt->idinvoicer] = new CompanyEntity($receipt->idinvoicer);
				}
				if ((bool)$as_collection)
					return new CompanyCollection($companies);
				else
					return $companies;
			}


	/**
	 * {@inheritDoc}
	 * @see  \Openy\Interfaces\Service\InvoiceInterface
	 */
    public function getReceipts(InvoiceEntity $invoice)
    {
    	return $this->receiptMapper->fetchAll(['idinvoice'=>$invoice->idinvoice]);
    }

    /**
     * DEPRECATED
	 * @deprecated Useless function
	 */
    public function getReceiptsInvoice(ReceiptCollection $receipts)
    {
    	$invoice = $this->getInvoice($receipts);
    	$invoice->receipts = new \ReceiptCollection([]);
    	if ($invoice->idinvoice)
    		$invoice->receipts = $receipts;
    	// TODO : It remaints to typecast the $invoice
    	return $invoice;
    	// TODO: Should we really want to return a FALSE value?
    }


}