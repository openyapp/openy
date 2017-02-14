<?php
namespace Openy\Service;

use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Select;
use ZF\ApiProblem\ApiProblemResponse;
use Openy\Exception;

use Zend\Http\Request;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;


class SecurityChain implements ServiceLocatorAwareInterface
{
    protected $currentUser;
    private $preferenceMapper;
    private $options;
    private $creditcardMapper;
    private $adapterMaster;
    private $adapterSlave;
    private $apicaller;
    
    use ServiceLocatorAwareTrait;
    
    public function __construct($currentUser)
    {
        $this->currentUser = $currentUser;  
              
    }
        
//     public function getPreference($property = null)
//     {
//         $pref = $this->repository->fetch($this->currentUser->getUser('iduser'));
//         if($property)
//             return $pref->$property;
        
//         return $pref;      
//     }
  
    
    
    /***************************************************************************************************/
    /**
     * Inject required mappers
     * 
     */
    private function initialize()
    {
        $this->preferenceMapper = $this->getServiceLocator()->get('Openy\V1\Rest\Preference\PreferenceMapper');
        $this->options          = $this->getServiceLocator()->get('Openy\Service\OpenyOptions');
        $this->creditcardMapper = $this->getServiceLocator()->get('Openy\Service\CreditCard');
        $this->adapterMaster    = $this->getServiceLocator()->get('dbMasterAdapter');
        $this->adapterSlave     = $this->getServiceLocator()->get('dbSlaveAdapter');
        $this->apicaller        = $this->getServiceLocator()->get('ElemApicaller\Service');
    }
    
    
    
    
    

    /***************************************************************************************************/
    
    public function verifySecurityChain($iduser, $data)
    {
        $this->initialize();
        
        if(!isset($data->userPin))
            $data->userPin = false;
        
        if(!isset($data->antifraudPin))
            $data->antifraudPin = false;
        
        $securityChain = array();
    
        if($this->isUserPinRequired())
        {
            $isUserPinVerified = $this->verifyUserPin($iduser, $data->userPin);
            $securityChain['chain']['userPin']=$isUserPinVerified;
        }
        else
            $isUserPinVerified['satisfied']=true;
    
        //print_r($isUserPinVerified);
        // Chain previous Pin
        
        if($this->isAntifraudPinRequired($iduser) && $isUserPinVerified['satisfied'] )
        {
            $isAntifraudPinVerified = $this->verifyAntifraud($iduser, $data->antifraudPin);
            
//             var_dump($isAntifraudPinVerified["satisfied"]);
//             die;
            if(!$isAntifraudPinVerified["satisfied"])
            {
                $code = $this->setAntifraudCode($iduser);
                $this->sendAntifraudCodeBySMS($iduser, $code);
            }
            else
                $code = $this->setAntifraudCode($iduser);
            
            $securityChain['chain']['antifraudPin'] = $isAntifraudPinVerified;      
        }
        else
            $isAntifraudPinVerified['satisfied'] = true;
            
        
        if($isUserPinVerified['satisfied'] === true && $isAntifraudPinVerified['satisfied'] === true)
            $securityChain['verification'] = true;
        else
            $securityChain['verification'] = false;
     
        return $securityChain;
    }
    
    private function isUserPinRequired()
    {
        // Check user preferences
        if($this->getServiceLocator()->get('CurrentUserPreferences')->payment_pin != null)
            return true;
        else
            return false;
    }
    
    private function isAntifraudPinRequired($iduser)
    {
        $suspect = $this->options->getIsAntifraudVerificationRequired();
            //TODO 
            // Implements testFraud Conditions
            //$suspect = $this->testfraudCondition1();
            //$suspect = $this->testfraudCondition2();
            //$suspect = $this->testfraudCondition3();   
        
        if($suspect)
        {
            /**
             * If any of the conditions return true, SEND SMS with antifraudCode
             * Store antifraudCode into opy_user_counter.
             */
            
          
            
        } 
        return $suspect;
    }
    
    private function getCounterData($iduser)
    {
        $select = new Select('opy_user_counter');
        $select->where(array('iduser' => $iduser));
        //var_dump($select->getSqlString());
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        
        $userData = $driverResult->current();
        return $userData;        
    }
    
    private function setAntifraudCode($iduser)
    {
        $userData = $this->getCounterData($iduser);
        
        $code = mt_rand(1000,9999);
        $data['iduser'] = $iduser;
        $data['antifraud_code'] = $code;
//         $data['antifraudsms_counter'] = 0;
        $data['antifraudsms_updated'] = date('Y-m-d G:i:s');

        // If not exist
        if(!isset($userData['iduser']))
        {
            //Insert
            $action = new Insert('opy_user_counter');
            $action->values($data);            
            //echo $action->getSqlString();            
            $statement = $this->adapterMaster->createStatement();
            $action->prepareStatement($this->adapterMaster, $statement);
            $driverResult = $statement->execute();
        }
        else
        {
            //Update
            if( (time() > strtotime($userData['antifraudsms_updated'])+$this->options->getDaysToResetPinsTries()) || 
                ($userData['antifraudsms_counter'] <= $this->options->getMaxNumberOfAntifraudSms())
                )
            {
                $action = new Update('opy_user_counter');
                $action->set($data);
                $action->where(array('iduser' => $iduser));            
                //echo $action->getSqlString();            
                $statement = $this->adapterMaster->createStatement();
                $action->prepareStatement($this->adapterMaster, $statement);
                $driverResult = $statement->execute();
            }
        }
        
        return $code;

    }
    
    
    
    
    
    
    
    
    
    
    private function sendAntifraudCodeBySMS($iduser, $code)
    {
        $userData = $this->currentUser->getUser();
        $counterData = $this->getCounterData($iduser);
//         print_r($counterData);
//         print_r($userData);
        if($counterData['antifraudsms_counter'] >= ($this->options->getMaxNumberOfAntifraudSms()))
            return false;
    
        $resource = $this->options->getSmsResource('messages');
        //         print_r($resource);
        $data = array(
            "originator" => "MessageBird",
            "body" => "The message to be sent",
            "reference" => "Openy antifraud code: ".$code,
            "recipients" => $userData['phone_number']
        );
    
//         print_r($data);
//         die;
        $this->apicaller->setHeaders($resource['headers']);
        $url = sprintf($this->options->getSmsUrl().$resource['endpoint']);
        $value = $this->apicaller->getResponse($url, $data, Request::METHOD_POST);
         
        // Increment sms counter
        $pinConfiguration = array('table' => 'opy_user_counter',
            'counterColumn' => 'antifraudsms_counter',
            'timestampColumn' => 'antifraudsms_updated',
            //'noMoreAtteptsCallback' => 'deleteAllActiveCreditcards',
            'maxNumberOfTries' => $this->options->getMaxNumberOfAntifraudSms()
        );
        $counter = $this->incrementCounter($pinConfiguration, $iduser);
        
        
        // print_r($value);
        if($this->options->getIsEnableVerifyWithSmsSendEmail())
        	   $this->sendGenericEmail($userData, "Código de anti fraude con SMS enviado al ".$userData['phone_number'].": ".$code);
         
        if(200 <= $value['code'] && 300 > $value['code'])
            return true;
        else
            return false;
         
    }
    
    
    
    
    public function sendGenericEmail($userData, $info)
    {
        $view = new ViewModel();
        $view->setTemplate('genericEmail');
        $view->setVariables(array('name' => $userData['first_name'], 'info' => $info));
    
        $transport = new SmtpTransport();
        $message = new Message();
        $message->addTo($userData['username'])
//         ->addFrom('appopeny@gmail.com')
        ->addFrom('openy@.com', 'Openy App')
        ->setSubject('Información de Openy')
        ->setBody($this->toHtml($view));
    
        $options = new SmtpOptions($this->options->getSmtpOptions());
    
        $transport->setOptions($options);
        $result = $transport->send($message);
    
        return $result;
    }
    
    private function initRenderer()
    {
        $renderer = new PhpRenderer();
        $resolver = new Resolver\TemplateMapResolver(array(
            'genericEmail' => __DIR__ . '/../../../view/mails/genericEmail.phtml',            
        ));
        $renderer->setResolver($resolver);
    
        return $renderer;
    }
    
    private function toHtml($view)
    {
        $renderer = $this->initRenderer();
        $html = new MimePart($renderer->render($view));
        $html->type = "text/html";
    
        $body = new MimeMessage();
        $body->setParts(array($html));
    
        return $body;
    }
    
    private function verifyAntifraud($iduser, $inputPin = null)
    {
        
        
        $pinConfiguration = array('table' => 'opy_user_counter',
                                    'counterColumn' => 'antifraudpin_tries',
                                    'timestampColumn' => 'antifraudpin_updated',
                                    //'noMoreAtteptsCallback' => 'deleteAllActiveCreditcards',
                                    'maxNumberOfTries' => $this->options->getMaxNumberOfPinsTries()
        );
       
        
        $counterData = $this->getCounterData($iduser);
//         print_r($counterData);
//         print_r($iduser);
//         print_r($inputPin);
        
        $verification = $this->verifyPin($pinConfiguration, $iduser, $counterData['antifraud_code'], $inputPin);
//         print_r($verification);
//         die;
        if($verification["satisfied"]===true)
        {
            $pinConfiguration = array('table' => 'opy_user_counter',
                'counterColumn' => 'antifraudsms_counter',
                'timestampColumn' => 'antifraudsms_updated',
                //'noMoreAtteptsCallback' => 'deleteAllActiveCreditcards',
                'maxNumberOfTries' => $this->options->getMaxNumberOfAntifraudSms()
            );
            $counter = $this->resetCounter($pinConfiguration, $iduser);
        }
        
        
        // Not more attempts
        if($verification['remaining_attempts'] === 0)
            return $verification;                            // Cancel ORDER
           
        
        return $verification;  
    }
    
    public function verifyUserPin($iduser, $inputPin = null)
    {
        $pinConfiguration = array('table' => 'opy_user_counter',
            'counterColumn' => 'pin_tries',
            'timestampColumn' => 'pin_updated',
            'noMoreAtteptsCallback' => 'deleteAllActiveCreditcards',
            'maxNumberOfTries' => $this->options->getMaxNumberOfPinsTries()
        );
    
        $preference = $this->preferenceMapper->fetch($iduser);
    
        if($preference instanceof ApiProblemResponse)
            throw new Exception\ApiProblemException($preference->getApiProblem()->toArray()['detail'],$preference->getStatusCode());
    
    
        $verification = $this->verifyPin($pinConfiguration, $iduser, $preference->payment_pin, $inputPin);
        // Not more attempts
        if($verification['remaining_attempts'] === 0)
            $this->deleteAllActiveCreditcards($iduser);
    
        return $verification;
    }
    
    
    
    
    /*********************************************************************************************************/
    
    
 
    
    
    
    private function deleteAllActiveCreditcards($iduser)
    {
        $preference=array("default_credit_card"=>null);
        $this->preferenceMapper->patch($iduser, $preference);
        $this->creditcardMapper->deleteAll();
        return;
    }   
    
    private function verifyPin($pinConfiguration, $iduser, $currentPin,  $inputPin = null)
    {
        if($inputPin == null)
            return array('satisfied'=>false, 'remaining_attempts'=>'unknown');
        //throw new Exception\InvalidArgumentException ("Not valid pin.", 500);
    
        if($currentPin != $inputPin)
        {
            $counter = $this->incrementCounter($pinConfiguration, $iduser);
            $tries = $pinConfiguration['maxNumberOfTries'] - $counter;
            return array('satisfied'=>false, 'remaining_attempts'=>$tries);
        }
        else
        {
            $counter = $this->resetCounter($pinConfiguration, $iduser);
            $tries = $pinConfiguration['maxNumberOfTries'] - $counter ;
            return array('satisfied'=>true, 'remaining_attempts'=>$tries);
        }
    
    }
    
    private function resetCounter($pinConfiguration, $iduser)
    {
        $action = new Update($pinConfiguration['table']);
        $action->set(array($pinConfiguration['counterColumn']=>0, $pinConfiguration['timestampColumn']=>date('Y-m-d G:i:s')), 'set');
        $action->where(array('iduser'=>$iduser));
         
//         echo $action->getSqlString();
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        return 0;
    }
    
    private function incrementCounter($pinConfiguration, $iduser)
    {
        $select = new Select($pinConfiguration['table']);
        $select->where(array('iduser' => $iduser));
        //var_dump($select->getSqlString());
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
    
        if( time() > strtotime($driverResult->current()[$pinConfiguration['timestampColumn']]) + $this->options->getDaysToResetPinsTries())
        {
            $this->resetCounter($pinConfiguration, $iduser);
        }
    
        if($driverResult->current()['iduser']!=null)
        {
            $max = new \Zend\Db\Sql\Expression($pinConfiguration['counterColumn']." + 1");
            $data = array('iduser'=>$iduser, $pinConfiguration['counterColumn']=> $max, $pinConfiguration['timestampColumn']=>date('Y-m-d G:i:s'));
    
            $action = new Update($pinConfiguration['table']);
            $action->set($data, 'set');
            $action->where(array('iduser'=>$iduser));
             
//             echo $action->getSqlString();
            $statement = $this->adapterMaster->createStatement();
            $action->prepareStatement($this->adapterMaster, $statement);
            $driverResult = $statement->execute();
        }
        else
        {
            $data = array('iduser'=>$iduser, $pinConfiguration['counterColumn']=> 1, $pinConfiguration['timestampColumn']=>date('Y-m-d G:i:s'));
            $action = new Insert($pinConfiguration['table']);
            $action->values($data);
             
//             echo $action->getSqlString();
            $statement = $this->adapterMaster->createStatement();
            $action->prepareStatement($this->adapterMaster, $statement);
            $driverResult = $statement->execute();
        }
    
    
        $select = new Select($pinConfiguration['table']);
        $select->where(array('iduser' => $iduser));
        //var_dump($select->getSqlString());
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
    
        //print_r($driverResult->current());
    
        return $driverResult->current()[$pinConfiguration['counterColumn']];
    }
}
