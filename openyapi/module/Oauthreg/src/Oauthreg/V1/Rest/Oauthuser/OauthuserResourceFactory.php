<?php
namespace Oauthreg\V1\Rest\Oauthuser;

class OauthuserResourceFactory
{
    public function __invoke($services)
    {
        return new OauthuserResource($services->get('Oauthreg\V1\Rest\Oauthuser\OauthuserMapper'));
    }
}
