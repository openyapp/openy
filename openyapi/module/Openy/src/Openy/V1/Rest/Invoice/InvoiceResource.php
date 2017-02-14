<?php
/**
 * InvoiceResource.
 * API Invoicing Endpoint, responding calls to /invoice
 * @link http:///#//module/Openy/1/rest/Invoice Invoice Endpoint
 * @filesource
 * @author XSubira <xsubira@openy.es>
 * @package Openy\API\Invoicing
 * @category Invoices
 *
 */
namespace Openy\V1\Rest\Invoice;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Openy\Model\Payment\ReceiptCollection;
use Openy\Model\Payment\ReceiptEntity;
use Openy\V1\Rest\Invoice\InvoiceHydrator;
use Openy\V1\Rest\Invoice\InvoiceEntity;
use Openy\Interfaces\Service\InvoiceServiceInterface;

/**
 * Invoice Endpoint Resource Class.
 * Responds to GET HTTP requests from APP (or API consumptions)
 * and fetches an invoice or a collection of them
 * Also responds to POST HTTP requests having the ability to
 * collect provided receipts in a single invoice
 * @see Openy\Invoicing
 * @uses  Openy\Service\Invoice Invoice Service
 * @uses  Openy\V1\Rest\Invoice\InvoiceMapper Invoice Mapper
 *
 */
class InvoiceResource extends AbstractResourceListener
{
	/**
	 * Invoice Service
	 * @var InvoiceInterface
	 */
    protected $invoiceService;
    protected $invoiceMapper;

    /**
     * Constructor.
     *
     * @param Openy\Service\Invoice $invoiceService Invoice Service used to produce new Invoices
     * @param Openy\V1\Rest\Invoice\InvoiceMapper $invoiceMapper  Invoice Mapper used to fetch Invoices
     */
    public function __construct(InvoiceServiceInterface $invoiceService, $invoiceMapper){
        $this->invoiceService = $invoiceService;
        $this->invoiceMapper = $invoiceMapper;
    }


    /**
     * (POST) CREATE a new invoice.
     * Takes an array with Receipt identifiers from given $data argument.
     *
     * @param  StdClass|array $data Must be an object having a "receipts" property
     * or an array having a key with same name
     * @return ApiProblem|InvoiceEntity Returns created Invoice or ApiProblem on error
     * @api
     */
    public function create($data)
    {
        $data = (array)$data;
        $receipts = array_key_exists('receipts', $data) ? (array)($data['receipts']) : [];
        $entities = [];
        foreach($receipts as $id){
            $entities[] = new ReceiptEntity($id);
        }      
        $receipts = new ReceiptCollection($entities);
        $result = $this->invoiceService->getInvoice($receipts);
        $receipts = $this->invoiceService->getReceipts($result);
        $result->receipts = new \Openy\V1\Rest\Receipt\ReceiptCollection($receipts->getAdapter());
        return $result;
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     *
     * (GET) FETCH an invoice.
     * Takes provided argument as the identifier for the Invoice to fetch.
     *
     * @param String $id Identifier for requested invoice or 'sample' for fetching an example Invoice
     * @return ApiProblem|InvoiceEntity Returns Fetched Invoice entity or ApiProblem on error
     * @api
     */
    public function fetch($id)
    {
        // Identificamos cuando se nos pide mostrar un ejemplo
        if ($id == 'sample'){
            $entity = new InvoiceEntity();
            return $entity->getSample();
        }

        $result = $this->invoiceMapper->fetch($id);

        if ($result == FALSE || $result->idinvoice == FALSE)
            return new ApiProblem(404, 'Invoice not found');

        return $result;
    }

    /**
     *
     * (GET) FETCH ALL invoices or a subset.
     *
     * @link http:///#//module/Openy/1/rest/Invoice Invoice Endpoint
     *
     * @param array $params Parameters used for delimiting the set.
     * Set "until" parameter with the top date for Invoices to list
     * @return ApiProblem|InvoiceCollection Returns a Collection containing fetched Invoices or ApiProblem on error
     * @source 3 8 Parses parameter "until" and forces it to be alike Ymd
     *
     * @api
     *
     */
    public function fetchAll($params = array())
    {

        if (isset($params['until'])){
            $filter = new \Zend\Filter\DateTimeFormatter();
            $filter->setFormat('Ymd');
            try{
                $params['until']=$filter->filter($params['until']);
            }catch(\Exception $e){
                return new ApiProblem(400, 'Invalid value for parameter "until"');
            }
        }
        return $this->invoiceMapper->fetchAll($params);
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
