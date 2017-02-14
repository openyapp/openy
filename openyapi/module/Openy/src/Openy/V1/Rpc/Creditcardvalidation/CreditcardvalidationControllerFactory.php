<?php
namespace Openy\V1\Rpc\Creditcardvalidation;

class CreditcardvalidationControllerFactory
{
    public function __invoke($controllers)
    {
 		$services = $controllers->getServiceLocator();
        $creditcardService = $services->get('Openy\Service\CreditCard');
        return new CreditcardvalidationController($creditcardService);
    }
}
