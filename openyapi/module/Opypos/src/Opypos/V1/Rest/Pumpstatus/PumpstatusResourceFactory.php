<?php
namespace Opypos\V1\Rest\Pumpstatus;

class PumpstatusResourceFactory
{
    public function __invoke($services)
    {
        return new PumpstatusResource($services->get('Opypos\V1\Rest\Pumpstatus\PumpstatusMapper'));
    }
}
