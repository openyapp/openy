<?php
namespace Opypos\V1\Rest\Client;

class ClientResourceFactory
{
    public function __invoke($services)
    {
        return new ClientResource();
    }
}
