<?php 
namespace Openy\V1\Rest\Refuel;

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
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class RefuelMapper implements ServiceLocatorAwareInterface//, LoggerAwareInterface
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;  
    protected $orderService;
    protected $currentUser;
    protected $securityChain;
    protected $refuelService;
    protected $priceMapper;
    protected $request;
    protected $refuelLogger = null;
    
    private $tableName      = 'opy_order';
    private $entity         = 'Openy\V1\Rest\Refuel\RefuelEntity';
    private $collection     = 'Openy\V1\Rest\Refuel\RefuelCollection';
    private $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    
    use ServiceLocatorAwareTrait;
//     use LoggerAwareTrait;
    
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, 
                                OrderServiceInterface $orderService, $currentUser, $securityChain, $refuelService, $priceMapper, $request)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;    
        $this->orderService     = $orderService;
        $this->currentUser      = $currentUser;
        $this->securityChain    = $securityChain;
        $this->refuelService    = $refuelService;
        $this->priceMapper      = $priceMapper;  
        $this->request          = $request;
        if($_SERVER['APPLICATION_ENV'] =='development')
        {
            // TODO remove log and set in a event
            $this->refuelLogger = new Logger;
            $this->refuelLogger->addWriter(new Stream('data/logs/refuellog.log'));
        }
    }
    
    
    /** 
     * TODO
     * THIS IS ONLY FOR TEST. REMOVE AFTER FINISH
     * 
     * @param unknown $filter
     * @return object
     */
    public function fetchAll($filter)
    {
        
        $select = new Select($this->tableName);
               
        /**
         * Filters
        */
        
//         echo $select->getSqlString();
    
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
    
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();        
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        
        $resultset = new HydratingResultSet;
        $resultset->setHydrator($hydrator);
        $resultset->setObjectPrototype($entity);
        $resultset->initialize($driverResult); 
        
        $paginatorAdapter = new DbSelect(
            $select,
            $this->adapterSlave,
            $resultset
        );
        
        $class = new \ReflectionClass($this->collection);
        $collection = $class->newInstance($paginatorAdapter);
        
        return $collection;
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
    
    
    private function extractPromotion($data)
    {
        $prices = $this->priceMapper->fetch($data->idoffstation);
        $prices = get_object_vars(json_decode($prices->prices->toJson()));
        foreach ($prices as $price)
        {
            if($price->opyProductType==$data->fueltype)
            {
                $promotions = $price->promotionPerValue;
                break;
            }
        }
        
        if($promotions)
            $promotions = get_object_vars($promotions);
        else
        {
            
            $promotion = new \stdClass();
            $promotion->idPromotion = null;
            $promotion->units = null;
            $promotion->originalPricePerUnit = $data->price;
            $promotion->value = $data->amount;
            $promotion->discountPerUnit = null;
            $promotion->discountPercentage = null;
            $promotion->pricePerUnit = null;
            $promotion->promPricePorcentage = null;
            $promotion->promPricePerIUnit = null;
            $promotion->promoUnits = null;
            $promotion->promType = null;
            $promotion->discount = 0;
            $promotion->priceToPay = $data->price;
            
            $promotions[$data->amount] = $promotion;
        }
        //print_r($promotions);
        
        if(isset($promotions[$data->amount]))
			return $promotions[$data->amount];
		else	// Return promotion cero
		{
			$promotion = new \stdClass();
            $promotion->idPromotion = null;
            $promotion->units = null;
            $promotion->originalPricePerUnit = $data->price;
            $promotion->value = $data->amount;
            $promotion->discountPerUnit = null;
            $promotion->discountPercentage = null;
            $promotion->pricePerUnit = null;
            $promotion->promPricePorcentage = null;
            $promotion->promPricePerIUnit = null;
            $promotion->promoUnits = null;
            $promotion->promType = null;
            $promotion->discount = 0;
            $promotion->priceToPay = $data->price;
            
            $promotions[$data->amount] = $promotion;
			
			return $promotions[$data->amount];
		}
		
    }
    
    public function insert($data)
    {
        $iduser = $this->currentUser->getUser('iduser');
    
        $request = $this->request;
        $headers = $request->getHeaders();
        $authentication = $headers->get('Authorization')
                                  ->getFieldValue();
        $authentication = explode('Bearer ',$authentication);
        
        if(isset($authentication[1]))
            $authentication = $authentication[1];
        else
            $authentication = false;
        
        //$this->getLogger()->debug('--------- Refuel BEGIN ---------');
        (null != $this->refuelLogger)?$this->refuelLogger->info('--------- Refuel BEGIN ---------'):null;
        
        $securityChain = $this->securityChain->verifySecurityChain($iduser, $data);
        if(!$securityChain['verification'])
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    307 ,
                    'Security Chain Required',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-307',
                    'Temporary redirect'
                    ,array('details'=>array($securityChain))
                )
            );
        }

        (null != $this->refuelLogger)?$this->refuelLogger->debug('Security Chain Required: TRUE'):null;
        // print_r($securityChain);
        
        
       
        /**
         * ---------------------------------------------------------------------
         * START ORDER
         * ---------------------------------------------------------------------
         */
        
        $order = new OrderEntity();
        $order->amount = $data->amount;
        $idopystation = $this->getOpyStation($data->idoffstation);
        
        $promotion = $this->extractPromotion($data);
//         print_r($promotion);
        $priceToRefuel = $promotion->discount + $data->amount;
//         print_r($priceToRefuel);
        $data->toRefuel = $priceToRefuel;
        $data->idopystation = $idopystation;
        //ssss
        //Calculate presetAmount
//         print_r($data);
//         die;
        $order->idopystation = $data->idopystation;
        
        
        try{
            $pref = $this->getServiceLocator()->get('Openy\Service\CurrentPreferences')->getPreference();
            $order->paymentmethodid = $pref->default_credit_card;
            $order = $this->orderService->registerOrder($order);
            (null != $this->refuelLogger)?$this->refuelLogger->debug('Order Registered: '.$order->idorder):null;
        }
        catch (DomainException $e)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    '400' ,
                    $e->getMessage(),
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request',
                    array('request'=>$data)
                )
            );            
        }
        
        /**
         * ---------------------------------------------------------------------
         * AUTHORIZE ORDER
         * ---------------------------------------------------------------------
         */
        
        $order = $this->orderService->authorizeOrder($order);
        (null != $this->refuelLogger)?$this->refuelLogger->debug('Order Authorized: '.json_encode($order->orderstatus)):null;
        
        if($order->orderstatus->status != $order::STATUS_AUTHORIZED)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    402 ,
                    'Bank error',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-402',
                    'Payment Required'
                    ,array('details'=>array('errorCode'=>$order->orderstatus->lastcode, 'errorMessage'=>$order->orderstatus->codemsg))
                )
            );
        } 

        try{
            /*
            $price = $this->refuelService->setPrice($iduser, $data ,$order);
            if(200 > $price['code'] && 300 <= $price['code'])
                throw new DomainException('Price not set', 500); 
            */
            
            
            // Get Price per product per amount
            $prices = $this->priceMapper->fetch($data->idoffstation);
            $price = $data->toRefuel;
//             print_r($prices);
            //ssss
            //Calculate presetAmount
//             die;
            (null != $this->refuelLogger)?$this->refuelLogger->debug('To refuel: '.json_encode($price)):null;
            
            $amount = $this->refuelService->presetAmount($iduser, $data ,$order);
            
            (null != $this->refuelLogger)?$this->refuelLogger->debug('To amount: '.json_encode($amount)):null;
            
            if(200 > $amount['code'] && 300 <= $amount['code'])
                throw new DomainException('Amount not set', 500);
            
            
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
        
        $allowRaisePump = true;
        $price = new Entity($promotion);
        $amount = new Entity($amount);        
        
        // Start counter
        $monitor_url = $this->options->getMonitorsUrl();
        $monitors = $this->options->getRefuelMonitors();
        
        $monitor = $monitors['raisepump'];
        //$composeCurl = 'curl -i -H "Accept: application/json" -H "Content-Type: '.$monitor['headers']['Content-Type'].'" -H "Authorization: Bearer '.$authentication.'" '.$monitor_url.$monitor['endpoint'].'/'.$idopystation.'/'.$data->pump.'/'.$data->fueltype.'/'.$order->idorder.' -k > /dev/null 2>/dev/null &';
        $composeCurl = 'curl -i -H "Accept: application/json" -H "Content-Type: '.$monitor['headers']['Content-Type'].'" -H "Authorization: Bearer '.$authentication.'" '.$monitor_url.$monitor['endpoint'].'/'.$idopystation.'/'.$data->pump.'/'.$data->fueltype.'/'.$order->idorder.' > /dev/null 2>/dev/null &';
		(null != $this->refuelLogger)?$this->refuelLogger->debug('Curl Call Raise: '.json_encode($composeCurl)):null;
        $salida = exec($composeCurl);
        
        
        
        return array('allowRaisePump'=>$allowRaisePump, 'refuel'=>$data ,'security'=> $securityChain, 'order'=>$order, 'price'=>$price, 'amount'=>$amount);
    }
    
    
    
    
    
    
    
    
    
}
