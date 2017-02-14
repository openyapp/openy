<?php
namespace Oauthreg\V1\Rpc\VerifyRecoverPassword;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel; 

class VerifyRecoverPasswordController extends AbstractActionController
{
    protected $recoverpasswordMapper;
        
    public function __construct($recoverpasswordMapper)
    {
        $this->recoverpasswordMapper = $recoverpasswordMapper;
    }
    
    public function verifyRecoverPasswordAction()
    {
    
        $token = $this->params()->fromRoute('token');
        if(empty($token))
            throw new \Exception ("Not valid link.", 404);
    
        $email = $this->params()->fromRoute('email');
        if(empty($email))
            throw new \Exception ("Not email provided.", 404);
    
        $result = $this->recoverpasswordMapper->verifyEmail($token, $email);
        
        return new ViewModel(array(
            'result' => $result,
        ));
    }
}
