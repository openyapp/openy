<?php
namespace Oauthreg\V1\Rpc\VerifySms;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class VerifySmsController extends AbstractActionController
{
    protected $registerMapper;
    protected $options;
    
    public function __construct($registerMapper, $options)
    {
        $this->registerMapper = $registerMapper;
        $this->options = $options;
    }
    
    public function verifySmsAction()
    {   
        $code = $this->params()->fromRoute('code');
        if(empty($code))
            throw new \Exception ("Not valid code.", 404);
        
        $iduser = $this->params()->fromRoute('iduser');
        if(empty($iduser))
            throw new \Exception ("Not iduser provided.", 404);
        
        $result = $this->registerMapper->verifySms($code, $iduser);
//         var_dump($result);
        if($result)
        {
            $sql = "INSERT INTO oauth_users (username, password, first_name, last_name, phone_number, iduser, token)
                        SELECT email, password, first_name, last_name, phone_number, iduser, token
                        FROM oauth_register
                        WHERE iduser='".$iduser."' AND code ='".$code."'";
            
            //echo $sql;
            $statement = $this->registerMapper->getAdapterMaster()->createStatement($sql);
            $result1 = $statement->execute();
        
            
            if($this->options->getIsEnableDeleteTemporalInfoAfterVerification())
                $this->registerMapper->deleteByid($iduser);
        
        }
        else
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'Not verifiable user',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request'
                )
            );
        }
        
        return new ViewModel(array(
            'result' => $result,
        ));
    }
}
