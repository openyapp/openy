<?php
namespace Openy\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Http\Request;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class HeaderAuthentication implements AdapterInterface
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

    public function extractPublicKey($authentication)
    {
        
        $authentication = explode(':',$authentication);
        $authentication = explode(' ',$authentication[0]);
        return $authentication[1];
    }
    
    public function extractSignature($authentication)
    {
    
        $authentication = explode(':',$authentication);
        return $authentication[1];
    }
    
    public function getHmac($request, $user)
    {
        $key = $user->privatekey;
        $data = $request->getMethod() . "\n" .
            $request->getUriString() . "\n" .
            $request->getVersion() . "\n" .
            //                     $request->getHeaders()->get('Authentication')->getFieldValue() . "\n" .
        $request->getContent() . "\n";
        
        $Sig = urlencode(base64_encode(hash_hmac('sha256', $data, $key, true)));
//         echo $Sig;
        
        //Signature = URL-Encode( Base64( HMAC-SHA1( YourSecretAccessKeyID, UTF-8-Encoding-Of( StringToSign ) ) ) );
        return $Sig;
        
    }
    
    public function authenticate()
    {
        $request = $this->request;
        $headers = $request->getHeaders();
               
        
        // Check Authorization header presence
        if (!$headers->has('Authentication')) {
//             return new Result(Result::FAILURE, null, array(
//                 'Authorization header missing'
//             ));            
            return new ApiProblemResponse(
                new ApiProblem(
                    401 ,
                    'Authentication header missing',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-401' ,
                    'Unauthorized'
                )
            );
        }
 
        // Check Authorization prefix
        $authentication = $headers->get('Authentication')
                                 ->getFieldValue();
        if (strpos($authentication, 'OPY') !== 0) {
//             return new Result(Result::FAILURE, null, array(
//                 'Missing OPY prefix'
//             ));
            return new ApiProblemResponse(
                new ApiProblem(
                    401 ,
                    'Missing OPY prefix',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-401' ,
                    'Unauthorized'
                )
            );
        }
   
//         echo "2";
        // Validate public key
//         print_r($authentication);
        $publicKey = $this->extractPublicKey($authentication);
        
        
        
//         print_r($publicKey);
        $user      = $this->repository
                          ->findByPublicKey($publicKey);
        
//         print_r($user);
        
        
        
        if (null === $user) {
            $code = Result::FAILURE_IDENTITY_NOT_FOUND;
//             return new Result($code, null, array(
//                 'User not found based on public key'
//             ));
            return new ApiProblemResponse(
                new ApiProblem(
                    403 ,
                    'User not found based on public key',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-401' ,
                    'Unauthenticated'
                )
            );
        }
//         echo "3";
        
//         $this->hashContent($request, $user->privatekey);
        // Validate signature
        $signature = $this->extractSignature($authentication);
        
//         print_r($signature);
        
        $hmac      = $this->getHmac($request, $user);
//         print_r($hmac);
        if ($signature !== $hmac) {
            $code = Result::FAILURE_CREDENTIAL_INVALID;
//             return new Result($code, null, array(
//                 'Signature does not match'
//             ));
            return new ApiProblemResponse(
                new ApiProblem(
                    403 ,
                    'Signature does not match',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-401' ,
                    'Unauthenticated'
                )
            );
        }
//         echo "4";
//         die("ALL OK");  
        return new Result(Result::SUCCESS, $user);
    }
}