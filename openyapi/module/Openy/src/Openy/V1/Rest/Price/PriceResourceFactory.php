<?php
namespace Openy\V1\Rest\Price;

class PriceResourceFactory
{
    public function __invoke($services)
    {
        return new PriceResource($services->get('Openy\V1\Rest\Price\PriceMapper'));
    }
}
