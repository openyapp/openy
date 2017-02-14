<?php
namespace Openy\V1\Rest\Invoice;

/**
 * @ignore
 */
class InvoiceResourceFactory
{
    public function __invoke($services)
    {
        $InvoiceService = $services->get('Openy\Service\Invoice');
        $InvoiceMapper = $services->get('Openy\V1\Rest\Invoice\InvoiceMapper');
        return new InvoiceResource($InvoiceService,$InvoiceMapper);
    }
}
