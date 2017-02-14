<?php
namespace Opypos\V1\Rest\Price;

class PriceResourceFactory
{
    public function __invoke($services)
    {
        return new PriceResource($services->get('Opypos\V1\Rest\Price\PriceMapper'));
    }
}
