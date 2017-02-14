<?php
namespace Oauthreg\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Http\Request;
use DomainException;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class CheckValidation implements AdapterInterface
{
    protected $request;
    protected $registerMapper;

//     public function __construct(Request $request, UserRepository $repository)
    public function __construct($request, $registerMapper)    
    {
//         echo "kakass";

//         echo "casi";
        $this->request    = $request;
        $this->registerMapper = $registerMapper;
    }
    
       
    public function authenticate()
    {
//         echo "kaka";
        $request = $this->request;
        $userInfo = json_decode($this->request->getContent()); 
        
        if(!isset($userInfo->username))
        {
            return false;
        }
        $userInfo->email = $userInfo->username;
        unset($userInfo->username);
        unset($userInfo->grant_type);
        unset($userInfo->client_id);
        
        
        $processRegisterAs = $this->registerMapper->recordRegisterBy($userInfo->email);
//         print_r($processRegisterAs);
        if($processRegisterAs == 'do_nothing')
        {        
            $entity = $this->registerMapper->getUserInfo($userInfo->email)->current();
//             print_r($entity);
           
//             return new ApiProblemResponse(
//                 new ApiProblem(
//                     400 ,
//                     'User registered but not validated',
//                     'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
//                     'Bad Request',
//                     array('user'=>$entity)
//                 )
//             );
        }
        if ($processRegisterAs == 'update')
        {
            $out = $this->registerMapper->updateSms((array)$userInfo);
            
            if($out instanceof ApiProblemResponse)
                return new ApiProblemResponse(
                    $out->getApiProblem()
                );

            return new ApiProblemResponse(
                new ApiProblem(
                    409 ,
                    'User registered but not validated. SMS sent.',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-409' ,
                    'Conflict',
                    array('user'=>$out)                        
                )
            );
        }
         
        return new Result(Result::SUCCESS, $processRegisterAs);
    }
}