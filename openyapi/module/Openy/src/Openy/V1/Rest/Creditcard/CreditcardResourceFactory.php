<?php
namespace Openy\V1\Rest\Creditcard;

class CreditcardResourceFactory
{

    public function __invoke($services)
    {
        return new CreditcardResource($services->get('Openy\Service\CreditCard'));
    }
}
