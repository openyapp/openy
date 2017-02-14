<?php
namespace Openy\V1\Rest\Creditcard;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

use DomainException;

use ZF\Rest\AbstractResourceListener;
use Openy\Model\Classes\CreditCardDataEntity as CreditCard;
/*use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;*/
use Openy\Interfaces\Service\CreditcardServiceInterface;

class CreditcardResource extends AbstractResourceListener
{
    protected $mapper;
    protected $service;

    public function __construct(CreditcardServiceInterface $creditcardService)
    {
        $this->service = $creditcardService;
        $this->mapper = $this->service->getRepository();
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $card = new CreditCard($data);
        $result = $this->service->registerCreditCard($card);

        switch(get_class($result)):
            case 'Zend\Json\Server\Error':
                return new ApiProblemResponse(
                        new ApiProblem(
                            $result->getCode() ,
                            $result->getMessage(),
                            'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                            null,
                            ['additional details'=>$result->getData()]
                        )
                    );
                break;
            default:
                return $result;
                break;
        endswitch;
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return $this->service->delete($id);
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
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
        $creditcard = $this->mapper->fetch($id);
        if ($creditcard == FALSE || $creditcard->idcreditcard == FALSE)
            return new ApiProblem(404, 'Card not found');
        return $creditcard;

        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        return $this->mapper->fetchAll($params);
        return new ApiProblem(405, 'The GET method has not been defined for collections');
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
        $creditcard = $this->mapper->update($id,$data);
        if ($creditcard->idcreditcard == FALSE)
            return new ApiProblem(404, 'Card Not found');
        return $creditcard;

        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
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
    }
}
