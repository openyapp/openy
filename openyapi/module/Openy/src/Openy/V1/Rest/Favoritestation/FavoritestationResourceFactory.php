<?php
namespace Openy\V1\Rest\Favoritestation;

class FavoritestationResourceFactory
{
    public function __invoke($services)
    {
        return new FavoritestationResource($services->get('Openy\V1\Rest\Favoritestation\FavoritestationMapper'));        
    }
}
