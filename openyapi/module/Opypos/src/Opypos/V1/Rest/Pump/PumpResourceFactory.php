<?php
namespace Opypos\V1\Rest\Pump;

class PumpResourceFactory
{
    public function __invoke($services)
    {
        return new PumpResource($services->get('Opypos\V1\Rest\Pump\PumpMapper'));
    }
}
