<?php
namespace Oauthreg\V1\Rest\Register;

class RegisterResourceFactory
{
    public function __invoke($services)
    {
        return new RegisterResource($services->get('Oauthreg\V1\Rest\Register\RegisterMapper'));        
    }
}
