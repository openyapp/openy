<?php
namespace Openy\V1\Rest\Fueltype;

class FueltypeResourceFactory
{
    public function __invoke($services)
    {
        return new FueltypeResource($services->get('Openy\V1\Rest\Fueltype\FueltypeMapper'));
    }
}
