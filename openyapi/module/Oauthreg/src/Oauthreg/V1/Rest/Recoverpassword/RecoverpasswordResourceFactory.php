<?php
namespace Oauthreg\V1\Rest\Recoverpassword;

class RecoverpasswordResourceFactory
{
    public function __invoke($services)
    {
        return new RecoverpasswordResource($services->get('Oauthreg\V1\Rest\Recoverpassword\RecoverpasswordMapper'));
    }
}
