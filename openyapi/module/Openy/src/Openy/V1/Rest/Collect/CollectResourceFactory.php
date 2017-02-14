<?php
namespace Openy\V1\Rest\Collect;

class CollectResourceFactory
{
    public function __invoke($services)
    {
        return new CollectResource($services->get('Openy\V1\Rest\Collect\CollectMapper'));
    }
}
