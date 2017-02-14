<?php

namespace Oauthreg\Service;

use Zend\Stdlib\AbstractOptions;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;

use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class VerifyEmail 
{
    protected $options;
    protected $apikey;
    
    public function __construct(AbstractOptions $options, $apikey)
    {
        $this->options		  = $options;
        $this->apikey         = $apikey;
    }
    
    public function sendGenericEmail($entity, $info)
    {
        $view = new ViewModel();
        $view->setTemplate('genericEmail');
        $view->setVariables(array('name' => $entity->first_name, 'info' => $info));
        
        $transport = new SmtpTransport();
        $message = new Message();
        $message->addTo($entity->email)
                ->addFrom('appopeny@gmail.com')
                ->setSubject('Información de Openy')
                ->setBody($this->toHtml($view));
    
        $options = new SmtpOptions($this->options->getSmtpOptions());
    
        $transport->setOptions($options);
        $result = $transport->send($message);
    
        return $result;
    }
    
    public function sendVerificationEmail($entity)
    {           
        $view = new ViewModel();
        $view->setTemplate('verificationEmail');
        if($this->options->getIsEnableXapikeyHeader())
        {
            $view->setVariables(array('activationURL' => $this->options->getVerificationFrontEndpoint(),
                                      'name' => $entity->first_name,
                                      'link' => urlencode($entity->token).'/'.$entity->email.'?X-ApiKey='.$this->apikey
            ));
        }
        else {
            $view->setVariables(array('activationURL' => $this->options->getVerificationFrontEndpoint(),
                'name' => $entity->first_name,
                'link' => urlencode($entity->token).'/'.$entity->email
            ));
        }
    
        
        $transport = new SmtpTransport();
        $message = new Message();
        $message->addTo($entity->email)
                ->addFrom('appopeny@gmail.com')
                ->setSubject('Activación de Openy')
                ->setBody($this->toHtml($view));
    
        $options = new SmtpOptions($this->options->getSmtpOptions());
        
        $transport->setOptions($options);
        $result = $transport->send($message);
        
        return $result;
    }
    
    public function sendRecoverPasswordToEmail($entity)
    {
        $view = new ViewModel();
        $view->setTemplate('recoverPasswordToEmail');
        $view->setVariables(array('newPassword' => $entity->newPassword));
    
        $transport = new SmtpTransport();
        $message = new Message();
        $message->addTo($entity->email)
        ->addFrom('appopeny@gmail.com')
        ->setSubject('New Password de Openy')
        ->setBody($this->toHtml($view));
    
        $options = new SmtpOptions($this->options->getSmtpOptions());
    
        $transport->setOptions($options);
        $result = $transport->send($message);
    
        return $result;
    }
    
    public function sendRecoverPasswordEmail($entity)
    {
        $view = new ViewModel();
        $view->setTemplate('recoverPasswordEmail');
        $view->setVariables(array('activationURL' => $this->options->getNewPsasswordFrontEndpoint(),
            'name' => $entity->first_name,
            'link' => urlencode($entity->token).'/'.$entity->email
        ));
    
        $transport = new SmtpTransport();
        $message = new Message();
        $message->addTo($entity->email)
        ->addFrom('appopeny@gmail.com')
        ->setSubject('Recover Password de Openy')
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
            'verificationEmail' => __DIR__ . '/../../../view/mails/verificationEmail.phtml',
            'recoverPasswordEmail' => __DIR__ . '/../../../view/mails/recoverpasswordEmail.phtml',
            'recoverPasswordToEmail' => __DIR__ . '/../../../view/mails/recoverpasswordtoEmail.phtml',
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
}
