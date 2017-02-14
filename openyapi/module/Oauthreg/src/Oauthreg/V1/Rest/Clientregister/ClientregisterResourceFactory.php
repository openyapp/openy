<?php
namespace Oauthreg\V1\Rest\Clientregister;

class ClientregisterResourceFactory
{
    public function __invoke($services)
    {
//         return new ClientregisterResource($services->get('Oauthreg\V1\Rest\Clientregister\ClientregisterMapper'));
        
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        //$entityManager = $services->get('Doctrine\ORM\EntityManager');
        /** @var \ZfcRbac\Service\AuthorizationService $authorizationService */
//         $authorizationService = $services->get('ZfcRbac\Service\AuthorizationService');
        
        $fooResource = new ClientregisterResource($services->get('Oauthreg\V1\Rest\Clientregister\ClientregisterMapper'));
//          $fooResource->setEntityManager($entityManager);
//         $fooResource->setAuthorizationService($authorizationService);
        
        return $fooResource;
    }
}
