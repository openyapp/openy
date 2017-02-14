<?php
namespace Openy\V1\Rest\Station;

class StationResourceFactory
{
    public function __invoke($services)
    {
        return new StationResource($services->get('Openy\V1\Rest\Station\StationMapper'));
    }
}
