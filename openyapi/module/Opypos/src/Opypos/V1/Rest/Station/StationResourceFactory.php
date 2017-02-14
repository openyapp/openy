<?php
namespace Opypos\V1\Rest\Station;

class StationResourceFactory
{
    public function __invoke($services)
    {
        return new StationResource($services->get('Opypos\V1\Rest\Station\StationMapper'));
    }
}
