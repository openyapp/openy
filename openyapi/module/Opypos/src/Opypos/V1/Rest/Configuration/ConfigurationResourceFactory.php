<?php
namespace Opypos\V1\Rest\Configuration;

class ConfigurationResourceFactory
{
    public function __invoke($services)
    {
        return new ConfigurationResource($services->get('Opypos\V1\Rest\Configuration\ConfigurationMapper'));
    }
}
