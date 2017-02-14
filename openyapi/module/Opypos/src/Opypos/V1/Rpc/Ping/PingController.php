<?php
namespace Opypos\V1\Rpc\Ping;

use DomainException;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\JsonModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

use Zend\Db\Sql\Select;

class PingController extends AbstractActionController
{
    protected $options;
    protected $apicaller;
    protected $adapterSlave;
    
    public function __construct($adapterSlave, $options, $apicaller)
    {
        $this->options          = $options;
        $this->apicaller        = $apicaller;
        $this->adapterSlave     = $adapterSlave;
    }    
    
    public function pingAction()
    {
        $id = $this->params()->fromRoute('idoffstation');
        try
        {
            $id = $this->getOpyStation($id);
        }
        catch (\Exception $e)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    404 ,
                    'Not Opy station found',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
                    'Not found'
                )
            );
        }
        
        
        $network = $this->options->getPosNetwork();
        if(!isset($network['opy_'.$id]['endpoint']))
        {
            //throw new DomainException('Not opy station set', 404);
            return new ApiProblemResponse(
                new ApiProblem(
                    404 ,
                    'Not Opy station configured',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
                    'Not found'
                )
            );
        }
        
        $url = $network['opy_'.$id]['endpoint']."ping";
        $value = $this->apicaller->getResponse($url);
        $data = $value['response']['ack'];
        
        return new JsonModel(array(
            'ack datetime' => date('Y/m/d H:i:s',$data),
        ));
    }
    
    private function getOpyStation($id)
    {
        $select = new Select('opy_station');
        $select->where(array('idoffstation' => $id));
        //var_dump($select->getSqlString());
    
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
    
        if (0 === count($driverResult)) {
            throw new DomainException('Not opy station set', 404);
        }
    
        return $driverResult->current()['idstation'];
    }
}
