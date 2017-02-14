<?php
namespace Openy\V1\Rest\Receipt;

class ReceiptResourceFactory
{
    public function __invoke($services)
    {
        $Service = $services->get('Openy\Service\Receipt');
        //$Mapper = $services->get('Openy\Mapper\Receipt');
        $Mapper = $services->get('Openy\V1\Rest\Receipt\ReceiptMapper');
        return new ReceiptResource($Service,$Mapper);
    }
}
