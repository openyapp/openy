<?php
namespace Opypos\V1\Rest\Closest;

class ClosestResourceFactory
{
    public function __invoke($services)
    {
        return new ClosestResource($services->get('Opypos\V1\Rest\Closest\ClosestMapper'));
    }
}
