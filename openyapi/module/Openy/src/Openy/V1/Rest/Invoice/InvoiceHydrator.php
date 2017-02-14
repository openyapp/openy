<?php
/**
 * InvoiceResource Hydrator.
 * API Invoicing Endpoint Entities Hydrator, hydrates entities before serving them
 * @link http:///#//module/Openy/1/rest/Invoice Invoice Endpoint
 * @filesource
 * @author XSubira <xsubira@openy.es>
 * @package Openy\API\Invoicing
 * @category Invoices
 *
 */
namespace Openy\V1\Rest\Invoice;

use Openy\Model\Hydrator\InvoiceHydrator as ParentHydrator;
use Zend\Stdlib\AbstractOptions;
use Openy\Interfaces\MapperInterface;

/**
 * Invoice Endpoint Hydrator Class.
 * Hydrates and extracts Invoice entities
 *
 * @link /phpdoc/packages/Openy.Invoicing.html Invoicing package
 * @uses  Openy\Model\Hydrator\InvoiceHydrator Invoice Hydrator (parent)
 * @uses  Openy\V1\Rest\Receipt\ReceiptMapper API Receipt Mapper
 * @uses  Openy\V1\Rest\Receipt\ReceiptCollection API Receipt Collection
 * @uses  Openy\V1\Rest\Invoice\InvoiceEntity API Invoice Entity
 *
 */
class InvoiceHydrator
	extends ParentHydrator
{

    /**
     * Receipt Mapper used to fetch receipts for a given invoice
     * @var MapperInterface
     * @ignore
     */
    protected $receiptMapper;

    /**
     * Constructor
     *
     * {@inheritDoc}
     *
     * @uses  Openy\Model\Hydrator\InvoiceHydrator::__construct() Parent constructor
     * @uses  Openy\Options\OpenyOptions OpenyOptions as $options param
     * @uses  Openy\Model\Hydrator\Strategy\UuidStrategy UuidStrategy applied on 'idinvoice'
     * @param MapperInterface $receiptMapper Mapper for fetching receipts
     * @param AbstractOptions $options Options for UuidStrategy
     *
     */
    public function __construct(MapperInterface $receiptMapper, AbstractOptions $options){
        parent::__construct($options);
        $this->receiptMapper = $receiptMapper;
    }

    /**
     * {@inheritDoc}
     *
     * Extracts "receipts" property after parent extraction
     *
     * @param  Openy\V1\Rest\Invoice\InvoiceEntity $object Invoice to extract
     * @return Array         Data extracted from Invoice
     */
    public function extract($object)
    {
        $result = parent::extract($object);
        if (is_null($result['receipts']) && !is_null($object->idinvoice))
            $receipts = $this->receiptMapper->fetchAll(['idinvoice'=>$object->idinvoice]);
            $result['receipts'] = new \Openy\V1\Rest\Receipt\ReceiptCollection($receipts->getAdapter());
        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * {@inheritDoc}
     * Adds hydration for "receipts" property
     *
     *
     * @param  Openy\V1\Rest\Invoice\InvoiceEntity $object Invoice to be hydrated
     * @return Openy\V1\Rest\Invoice\InvoiceEntity Invoice once hydrated
     */
    public function hydrate(array $data, $object)
    {
        $result = parent::hydrate($data,$object);

        if (is_null($result->receipts) && !is_null($object->idinvoice)){
            $receipts = $this->receiptMapper->fetchAll(['idinvoice'=>$object->idinvoice]);
            $result->receipts = new \Openy\V1\Rest\Receipt\ReceiptCollection($receipts->getAdapter());
        }
        return $result;
    }


}