<?php
namespace Oauthreg\V1\Rpc\VerifyEmail;

use Zend\Mvc\Controller\AbstractActionController;
// use Zend\View\Model\ViewModel;
use ZF\ContentNegotiation\ViewModel;

class VerifyEmailController extends AbstractActionController
{
    protected $registerMapper;
    protected $options;
    
    public function __construct($registerMapper, $options)
    {
        $this->registerMapper = $registerMapper;
        $this->options = $options;
    }
    
    public function verifyEmailAction()
    {
        
        $token = $this->params()->fromRoute('token');
        if(empty($token))
            throw new \Exception ("Not valid link.", 404);
        
        $email = $this->params()->fromRoute('email');
        if(empty($email))
            throw new \Exception ("Not email provided.", 404);
        
        if($this->options->getIsEnableXapikeyHeader())
        {
            $apikey = $this->params()->fromQuery('X-ApiKey');
            if(empty($apikey))
                throw new \Exception ("Not apikey provided.", 404);
            
        }
        
        
        
        $result = $this->registerMapper->verifyEmail($token, $email);
        
        if($result)
        {
            $sql = "INSERT INTO oauth_users (username, password, first_name, last_name, phone_number, iduser, token) 
                        SELECT email, password, first_name, last_name, phone_number, iduser, token
                        FROM oauth_register 
                        WHERE email='".$email."' AND token ='".$token."'";
            $statement = $this->registerMapper->getAdapterMaster()->createStatement($sql);
            $result1 = $statement->execute();
            
            if($this->options->getIsEnableXapikeyHeader())
            {
                $sql = "SELECT privatekey FROM app_register WHERE publickey='".$apikey."'";
                $statement = $this->registerMapper->getAdapterMaster()->createStatement($sql);
                $result0 = $statement->execute();            
                $vals = $result0->current();
                
                $sql = "UPDATE oauth_clients SET user_id = '".$email."' WHERE client_id ='".$vals['privatekey']."'";
                $statement = $this->registerMapper->getAdapterMaster()->createStatement($sql);
                $result2 = $statement->execute();
            }
            
            if($this->options->getIsEnableDeleteTemporalInfoAfterVerification())
                $this->registerMapper->delete($email);
            
        }
        else 
        {
            $this->registerMapper->delete($email);            
        }
        
        return new ViewModel(array(
            'result' => $result, 
        ));
    }
}
