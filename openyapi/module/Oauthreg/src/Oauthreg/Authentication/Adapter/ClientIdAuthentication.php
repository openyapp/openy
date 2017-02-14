<?php
namespace Oauthreg\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Http\Request;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ClientIdAuthentication implements AdapterInterface
{
    protected $request;
    protected $repository;

//     public function __construct(Request $request, UserRepository $repository)
    public function __construct($request, $repository)    
    {
//         echo "kakass";

//         echo "casi";
        $this->request    = $request;
        $this->repository = $repository;
    }
    
       
    public function authenticate()
    {
        $request = $this->request;
        $headers = $request->getHeaders();
    
        // Check Authorization header presence
        if (!$headers->has('X-ApiKey')  AND empty($this->request->getQuery('X-ApiKey')))
        {          
            return new ApiProblemResponse(
                new ApiProblem(
                    401 ,
                    'Apikey header missing',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-401' ,
                    'Unauthorized'
                )
            );
        }
        
 
        // Check Authorization prefix
        if(empty($this->request->getQuery('X-ApiKey')))
        {
            $apikey = $headers->get('X-ApiKey')
                              ->getFieldValue();
        }
        else
        {
            $apikey = $this->request->getQuery('X-ApiKey');
        }       
        
            
        
        
        $user      = $this->repository
                          ->findByPublicKey($apikey);

        if (null === $user or !$user) {
            $code = Result::FAILURE_IDENTITY_NOT_FOUND;
            return new ApiProblemResponse(
                new ApiProblem(
                    403 ,
                    'Client not found',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-401' ,
                    'Unauthenticated'
                )
            );
        }

        return new Result(Result::SUCCESS, $user);
    }
}