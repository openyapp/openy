<?php
/**
 * InvoiceResource Mapper.
 * API Invoicing Endpoint Mapper, exchanges entities with model under API demand
 * @link http:///#//module/Openy/1/rest/Invoice Invoice Endpoint
 * @filesource
 * @author XSubira <xsubira@openy.es>
 * @package Openy\API\Invoicing
 * @category Invoices
 *
 */
namespace Openy\V1\Rest\Invoice;

use Openy\Model\Invoice\InvoiceMapper as ParentMapper;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Invoice Endpoint Mapper Class.
 * Creates and Fetches Invoice entities from Model
 *
 * @see /phpdoc/packages/Openy.Invoicing.html Invoicing package
 * @uses  Openy\V1\Rest\Invoice\InvoiceCollection API Invoice Collection
 * @uses  Openy\V1\Rest\Invoice\InvoiceEntity API Invoice Entity
 * @uses  Openy\V1\Rest\Invoice\InvoiceHydrator API Invoice Hydrator
 * @uses  Openy\Model\Invoice\InvoiceMapper Invoice Mapper (parent)
 *
 */
class InvoiceMapper
	extends ParentMapper
{
    /**
     * Collection class for fetchAll
     * @var string
     * @ignore
     */
	protected $collection     = 'Openy\V1\Rest\Invoice\InvoiceCollection';

    /**
     * Entity class for fetch, insert or update
     * @var string
     * @ignore
     */
	protected $entity         = 'Openy\V1\Rest\Invoice\InvoiceEntity';

    /**
     * Hydrator instance for entities (InvoiceEntity)
     * @var HydratorInterface
     * @ignore
     */
    protected $invoiceHydrator;

    /**
     * SET Invoice Hydrator
     * @param HydratorInterface $invoiceHydrator [description]
     */
    public function setInvoiceHydrator(HydratorInterface $invoiceHydrator){
        $this->invoiceHydrator = $invoiceHydrator;
        return $this;
    }

    /**
     * GET Invoice Hydrator
     * @return [type] [description]
     */
    public function getInvoiceHydrator(){
        return $this->invoiceHydrator;
    }

    protected function fetchGetHydratorInstance(){
        return $this->getInvoiceHydrator();
    }

    protected function fetchAllGetEntityInstance(){
        $entity = parent::fetchAllGetEntityInstance();
        unset($entity->receipts);
        return $entity;
    }

    protected function fetchAllBuildQuerySetFilters($filters,&$query){
        parent::fetchAllBuildQuerySetFilters($filters,$query);
        $query->where(
                      [$this->tableAliasName.'.iduser'=>$this->currentUser->getUser('iduser')]
                      );
        return;
    }

    protected function fetchBuildQuerySetWhere($id,&$query,$where=[]){
        $query = parent::fetchBuildQuerySetWhere($id,$query,$where=[]);
        $query->where(
                      [$this->tableAliasName.'.iduser'=>$this->currentUser->getUser('iduser')]
                      );
        return $query;
    }


}
