<?php
namespace Openy\V1\Rpc\GetInvoice;

class GetInvoiceControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        $options = $services->get('Openy\Service\OpenyOptions');
        $invoiceService = $services->get('Openy\Service\Invoice');
        return new GetInvoiceController($options, $invoiceService);
    }
}
