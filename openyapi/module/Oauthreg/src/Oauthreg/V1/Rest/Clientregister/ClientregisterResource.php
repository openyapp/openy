<?php
namespace Oauthreg\V1\Rest\Clientregister;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
// use ZfcRbac\Service\AuthorizationService;

// use Oauthreg\Rbac\OAuthUserEntity;

// use ZfcRbac\Service\AuthorizationServiceAwareInterface;
// use ZfcRbac\Service\AuthorizationServiceAwareTrait;

class ClientregisterResource extends AbstractResourceListener //implements AuthorizationServiceAwareInterface
{
    protected $mapper;
//     protected $authorizationService;
    
//     use AuthorizationServiceAwareTrait;
    
    public function __construct($mapper)
    {
        $this->mapper = $mapper;
    }
    
//     public function setAuthorizationService(AuthorizationService $authorizationService)
//     {
//         $this->authorizationService = $authorizationService;
//         return $this;
//     }
    
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return $this->mapper->save($data);
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        //return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
        return $this->mapper->delete($id);
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        //return new ApiProblem(405, 'The GET method has not been defined for individual resources');
        return $this->mapper->fetch($id);
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
//         var_dump($this->authorizationService->getIdentity()->getRoles());
//         print_r($this->getIdentity());
//         print_r($this->authorizationService->getIdentity());
//         $authResult = $this->authorizationService->isGranted(OAuthUserEntity::PERMISSION_CAN_DO_FOO);
//         $authResult = $this->getAuthorizationService()->isGranted('canDoFoo');
//         if (!$authResult) {
//             return new ApiProblem(403, 'You don\'t have a permission to do fetchAll Clintregister.');
//         }
        
        //return new ApiProblem(405, 'The GET method has not been defined for collections');
        return $this->mapper->fetchAll($params);
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
        //return $this->mapper->save($data, $id);
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
        //return $this->mapper->save($data, $id);
    }
}
