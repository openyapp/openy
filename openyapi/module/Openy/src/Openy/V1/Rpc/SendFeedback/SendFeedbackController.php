<?php
namespace Openy\V1\Rpc\SendFeedback;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\JsonModel;
use ZF\ContentNegotiation\ViewModel;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class SendFeedbackController extends AbstractActionController
{
    
    protected $options;
    
    public function __construct($options)
    {
        $this->options          = $options;
    }
    
    
    public function sendFeedbackAction()
    {
        $view = new ViewModel();
        $view->setTerminal(true);
        
        $data = $this->bodyParams();
        $receivers = $this->options->getEmailReceivers();
        if(isset($receivers[$data['to']]))
        {
            $to = $receivers[$data['to']];            
        }
        else {
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'Receiver not recognized',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request'
                )
            ); 
        }
        
        
        
        $transport = new SmtpTransport();
        $message = new Message();
        $message->addTo($to)
                ->addFrom('openy@.com', 'Openy App')
                ->setSubject($data['subject'])
                ->setReplyTo($data['from'])
                ->setBody($data['body']);
        
        $options = new SmtpOptions($this->options->getSmtpOptions());
        
        $transport->setOptions($options);
        $result = $transport->send($message);
        
        //var_dump($result);
        
        
        
        if($result !== NULL)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'Sent error:'.$result,
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request'
                )
            );
        }
        else
            return new JsonModel(array(
                'result' => 'true',
            ));

    }
}
