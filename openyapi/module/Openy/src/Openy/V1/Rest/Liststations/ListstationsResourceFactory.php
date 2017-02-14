<?php
namespace Openy\V1\Rest\Liststations;

class ListstationsResourceFactory
{
    public function __invoke($services)
    {
        return new ListstationsResource($services->get('Openy\V1\Rest\Liststations\ListstationsMapper'));
    }
}
