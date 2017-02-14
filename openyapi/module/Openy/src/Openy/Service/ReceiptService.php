<?php
/**
 * Service.
 * Receipts Service
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Receipts
 * @see Openy\Module
 *
 */
namespace Openy\Service;

// ReceiptServiceInterface function arguments
use Openy\Model\Payment\ReceiptEntity;

// Methods internal uses
use Openy\Model\Hydrator\ReceiptHydrator;
use Rhumsaa\Uuid\Uuid;

// Constructor Arguments
use Openy\Interfaces\MapperInterface;
use Zend\Stdlib\AbstractOptions;

// Extends and Implements
use Openy\Interfaces\Service\ReceiptServiceInterface;
use Openy\Interfaces\Properties\OptionsInterface;
use Openy\Traits\Properties\OptionsTrait;


/**
 * ReceiptService.
 * Implements ReceiptServiceInterface
 *
 * @uses Openy\Interfaces\Service\ReceiptServiceInterface ReceiptServiceInterface
 * 
 * @uses Openy\Model\Payment\ReceiptEntity Receipt Entity
 * @see \Openy\Model\Payment\ReceiptCollection Receipts Collection
 * @see \Openy\V1\Rest\Invoice\InvoiceEntity Invoice Entity
 * @see Openy\Service\ReceiptService Openy Receipt Service class
 * @see \Openy\Interfaces\Service\PaymentService\Interface Payment Service Interface
 * @see \Openy\Service\PaymentService Payment Service class
 *
 */
class ReceiptService
	implements  ReceiptServiceInterface,
				OptionsInterface 		   	
{

	use OptionsTrait;
	
	/**
	 * Receipt repository Mapper
	 * @var MapperInterface
	 */
	protected $receiptMapper;	

	public function __construct(MapperInterface $receiptMapper, AbstractOptions $options){
		$this->receiptMapper = $receiptMapper;
		$this->setOptions($options);
	}


	public function locateReceipt(ReceiptEntity $receipt){
		$result = clone $receipt;
		$result = $this->receiptMapper->locate($result);
		return $result;
	}

	/**
	 * {@inheritedDoc}
	 */
	public function getReceipt(ReceiptEntity $receipt){
		$result = clone $receipt;

		if (!$this->receiptMapper->exists($result,$fetch_if_exists = true)){
			$hydrator = new ReceiptHydrator($this->getOptions());
			$data = $hydrator->extract($receipt);
			$data = (object)$data;
		    $result = $this->receiptMapper->insert($data);
		}

		return $result;
	}

	/**
	 * {@inheritedDoc}
	 */
	public function alterReceipt(ReceiptEntity $receipt){
		$result = clone $receipt;
		if ($this->receiptMapper->exists($result,$fetch_if_exists = true)){
			$hydrator = new ReceiptHydrator($this->getOptions());
			$data = $hydrator->extract($receipt);
			$data = (object)$data;
		    $result = $this->receiptMapper->update($receipt->receiptid,$data);
		}
		else
			$result = new ReceiptEntity;
		return $result;
	}

	/**
	 * {@inheritedDoc}
	 */
	public function hasTemporaryNumber(ReceiptEntity $receipt){
		$number = $receipt->receiptposid;
		return Uuid::isValid($number);
	}


	/**
	 * Locates a receipt by its number
	 * @param  ReceiptEntity $receipt [description]
	 * @return [type]                 [description]
	 */
	public function receiptNumberExists(ReceiptEntity $receipt){
		$receiptToSearch = clone $receipt;
		$receiptToSearch->receiptid = null;
		$receiptToSearch->idpayment = null;
		return $this->receiptMapper->exists($receiptToSearch);
	}

}