<?php

namespace TpvVirtual\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Zend\Soap\Server;

use TpvVirtual\Model\SerClsWSEntrada;
use TpvVirtual\Model\XmlModel;

class SOAPServerController extends AbstractActionController
{
    protected $acceptCriteria = array(
        'AP_XmlStrategy\View\Model\XmlModel' => array(
            'application/xml',
        ),
    );

    protected $options = [
                            'soap_version' => SOAP_1_1,
                            'encoding' => 'UTF-8',
                            'uri'=>'http:///soap/wsdl',
    ];
    protected $SOAPServerModel;

    public function __construct(){
        $this->SOAPServerModel = new SerClsWSEntrada();
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $server = new Server(null/*'http:///soap/wsdl'*/,$this->options);
            $server->setClass(get_class($this->SOAPServerModel));

            $server->setReturnResponse(true);
            $response = $server->handle($request->getContent());
            if ($response instanceof \SoapFault) {
                throw $response;
            }
            else {
                $view = new XmlModel(['response'=>$response]);
                $view->setRootNode('response');
                $view->setVersion('1.0');
                $view->setEncoding('UTF-8');
                $view->setTerminal(true);
                return $view;
            }
            return $response;
        }
        else{
            return $this->wsdlAction();
        }
    }

    public function wsdlAction()
    {
        $sm = $this->getServiceLocator();
        $config = $sm->get('Config');
        $content = $config['view_manager']['template_map']['wsdl'];
        $response = new \Zend\Http\Response();
        $response->getHeaders()->addHeaderLine('Content-Type', 'text/xml; charset=utf-8');
        $response->setContent(file_get_contents($content));
        return $response;
    }


}