<?php 
namespace Openy\V1\Rest\Collect;

use DomainException;
use InvalidArgumentException;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\AdapterInterface; 
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\AbstractOptions;
use Zend\Db\Sql\Predicate\IsNotNull;
use Openy\Interfaces\Service\OrderServiceInterface;
use Openy\Model\Order\OrderEntity;
use ZF\Hal\Entity;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class CollectMapper implements ServiceLocatorAwareInterface
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;  
    protected $orderService;
    protected $currentUser;
    protected $securityChain;
    protected $refuelService;
    protected $refuelLogger = null;
    
    private $tableName      = 'opy_order';
    private $entity         = 'Openy\V1\Rest\Collect\CollectEntity';
    private $collection     = 'Openy\V1\Rest\Collect\CollectCollection';
    private $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    
    use ServiceLocatorAwareTrait;
    
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, 
                                OrderServiceInterface $orderService, $currentUser, $securityChain, $refuelService)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;    
        $this->orderService     = $orderService;
        $this->currentUser      = $currentUser;
        $this->securityChain      = $securityChain;
        $this->refuelService      = $refuelService;
        
        if($_SERVER['APPLICATION_ENV'] =='development')
        {
            // TODO remove log and set in a event
            $this->refuelLogger = new Logger;
            $this->refuelLogger->addWriter(new Stream('data/logs/refuellog.log'));
        }
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
    
    public function insert($data)
    {
        (null != $this->refuelLogger)?$this->refuelLogger->debug('start collect insert: '. json_encode($data) ):null;
        
       $userData = $this->currentUser->getUser();
       
       (null != $this->refuelLogger)?$this->refuelLogger->debug('userData: '.json_encode($userData)):null;
       
       try{
            
            $data->idopystation = $this->getOpyStation($data->idoffstation);
            $collect = $this->refuelService->collectRefuel($data->idopystation, $data->pump, $data->fueltype, $userData);
            //print_r($collect);
        }
        catch (DomainException $e)
        {           
            return new ApiProblemResponse(
                new ApiProblem(
                    $e->getCode() ,
                    $e->getMessage(),
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-500' ,
                    'Internal Server Error',
                    array('request'=>$data)
                )
            );        
        }
        //print_r($collect);
        
        //TODO: Inform app developers about changes in the summary
        // Combustible -> Product
        // Precio -> Or Base imponible
        // IVAAmount -> added
        
        
        // TODO: Hardcode IVA percentage. Must be from pos configuration.
        
        (null != $this->refuelLogger)?$this->refuelLogger->debug('collect: '.json_encode($collect)):null;
        
        $data->date = date('d//m//Y G:i:s');
        $sumary = array (
            'data'		=> serialize($data),
            'details' 	=> array(
                "Fecha"		=> $collect['date'],
                "Product"	=> $data->fueltype,
                "Precio/lt"	=> $collect['price_per_unit'],
                "Litros" 	=> $collect['units'],
                //"Precio" 	=> $collect['price'],
                "Precio" 	=> (($collect['price_per_unit'] * $collect['units']) / 1.21),
                "IVA" 		=> "21%",
                "IVAAmount" => (($collect['price_per_unit'] * $collect['units']) / 1.21) * 0.21,
                "Total"		=> $collect['total'],
                "Ahorro" 	=> $collect['discount']
            ),
        );
        
        
        $details = array('summary'=>$sumary, 
                        'amount'=>$collect['total'],
                        'deliverycode'=>$collect['id_sell']            
        );
        
        $order = $this->getServiceLocator()->get('Openy\OrderMapper')->fetch($data->idorder);
        
        (null != $this->refuelLogger)?$this->refuelLogger->debug('order: '.json_encode($order)):null;
        
        (null != $this->refuelLogger)?$this->refuelLogger->debug('order ref status : '.$order::STATUS_PAYED):null;
        (null != $this->refuelLogger)?$this->refuelLogger->debug('order status : '.$order->orderstatus):null;
        
        if($order->orderstatus == $order::STATUS_PAYED)
        {
            $order = $this->orderService->receiptOrder($order);
                        return new ApiProblemResponse(
                            new ApiProblem(
                                202 ,
                                'This Order is already paid',
                                'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-202',
                                'No Content',
                                array('collect'=> $collect, 'order'=>$order)
                            )
                        );
        }

        (null != $this->refuelLogger)?$this->refuelLogger->debug('to collect details: '.json_encode($details)):null;
        
        $order = $this->collectRefuel($order, $details, $data->idoffstation);
        
        (null != $this->refuelLogger)?$this->refuelLogger->debug('order after collect: '.json_encode($order)):null;
        
        
        return $order;
    }
    
    public function collectRefuel($order, $data, $idoffstation)
    {

        if($order->orderstatus != $order::STATUS_AUTHORIZED)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    500 ,
                    'This Order has not previous payment authorization',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-500',
                    'Internal Server Error'                   
                )
            );
        } 
        
        /**
         * ---------------------------------------------------------------------
         * DELIVER ORDER
         * ---------------------------------------------------------------------
         */
        $order->summary = $data['summary'];
        $order->amount = $data['amount'];
        $order->deliverycode = $data['deliverycode'];               // POS ticket
//         $order->deliverycode = $idoffstation.'-'.$idsell;               // POS ticket
        $order = $this->orderService->deliverOrder($order);
        
        (null != $this->refuelLogger)?$this->refuelLogger->debug('Delivery Order done: '.json_encode($order)):null;
        
        /**
         * ---------------------------------------------------------------------
         * PAY ORDER
         * ---------------------------------------------------------------------
        */
        $order = $this->orderService->payOrder($order);
        
        (null != $this->refuelLogger)?$this->refuelLogger->debug('Pay Order done: '.json_encode($order)):null;
        
        /**
         * ---------------------------------------------------------------------
         * RECEIPT ORDER
         * ---------------------------------------------------------------------
        */
        $order = $this->orderService->receiptOrder($order);
        
        (null != $this->refuelLogger)?$this->refuelLogger->debug('Receipt Order done: '.json_encode($order)):null;
        
        /**
         * ---------------------------------------------------------------------
         * END ORDER
         * ---------------------------------------------------------------------
        */
        
//         $order = $this->orderService->invoiceOrder(array $receipt);
//         $order = $this->receiptService->invoiceOrder(array $receipt);
        
        return $order;        
    }
    
}