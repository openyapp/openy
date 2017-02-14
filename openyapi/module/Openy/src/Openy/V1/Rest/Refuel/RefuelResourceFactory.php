<?php
namespace Openy\V1\Rest\Refuel;

class RefuelResourceFactory
{
    public function __invoke($services)
    {
        return new RefuelResource($services->get('Openy\V1\Rest\Refuel\RefuelMapper'));
    }
}
