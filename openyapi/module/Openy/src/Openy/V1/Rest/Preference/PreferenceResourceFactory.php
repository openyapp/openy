<?php
namespace Openy\V1\Rest\Preference;

class PreferenceResourceFactory
{
    public function __invoke($services)
    {
        return new PreferenceResource($services->get('Openy\V1\Rest\Preference\PreferenceMapper'));
    }
}
