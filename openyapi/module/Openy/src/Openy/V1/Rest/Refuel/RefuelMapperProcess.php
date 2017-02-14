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

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class RefuelMapper implements ServiceLocatorAwareInterface
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;  
    protected $orderService;
    protected $currentUser;
    protected $securityChain;
    
    private $tableName      = 'opy_order';
    private $entity         = 'Openy\V1\Rest\Refuel\RefuelEntity';
    private $collection     = 'Openy\V1\Rest\Refuel\RefuelCollection';
    private $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    
    use ServiceLocatorAwareTrait;
    
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, 
                                OrderServiceInterface $orderService, $currentUser, $securityChain)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;    
        $this->orderService     = $orderService;
        $this->currentUser      = $currentUser;
        $this->securityChain      = $securityChain;
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
    
    public function insert($data)
    {
        $iduser = $this->currentUser->getUser('iduser');
        /**
         * ---------------------------------------------------------------------
         * SECURITY CHAIN VALIDATION
         * ---------------------------------------------------------------------
         *
         {
         "pump":"3",
         "amount": "20",
         "idopystation": "1",
         "fueltype": "5",
         "antifraudPin":"1560"
         }
         */
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
        print_r($securityChain);
    }
    
    
    public function insert2($data)
    {
        $iduser = $this->currentUser->getUser('iduser');
        
        /**
         * ---------------------------------------------------------------------
         * SECURITY CHAIN VALIDATION
         * ---------------------------------------------------------------------
         *
         {
         "pump":"3",
         "amount": "20",
         "idopystation": "1",
         "fueltype": "5",
         "antifraudPin":"1560"
         }
         */
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
        print_r($securityChain);
        /**
         * ---------------------------------------------------------------------
         * START ORDER
         * ---------------------------------------------------------------------
         */
        $order = new OrderEntity();
        $order->amount = $data->amount;
        $order->idopystation = $data->idopystation;
        
        $order = $this->orderService->registerOrder($order);
        $order = $this->orderService->authorizeOrder($order);
        
        
        
        
        
        
        
        /**
         * ---------------------------------------------------------------------
         * VALIDATE CREDITCARD
         * ---------------------------------------------------------------------
         */
        // Call creditcard
        /*
        {
            "pan" : "panpan",
            "year" : 17,
            "month" : 12,
            "cardusername" : "sample user name",
            "cvv" : "123"
        }
        */
        
        /**
         * ---------------------------------------------------------------------
         * REGISTER ORDER
         * ---------------------------------------------------------------------
         */
        //$order->paymentmethod = 1;
        //$order->idorder = "231123126";
        /* 
                 {
                    "idopystation": "1",
                  	"pump":"3", 
                    "fueltype": "G95",
                    "amount": "20",
                	"email":"moneto@openy.es",
                	"antifraudPin":"8375",                    
                    "codigocliente":"OPY_19",
                      "nif":"X6665666P",
                      "nombre":"UserName19",
                      "direccion":"Addres19",
                      "codigopostal":"CP19",
                      "poblacion":"locality19",
                      "telefono1":"phone19"
                }
         */      
          
        $order = new OrderEntity();        
        $order->amount = $data->amount;
        $order->idopystation = $data->idopystation;
        
        $order = $this->orderService->registerOrder($order);    // Crear vacia order
        
//         print_r($order);
//         die("kaka");
        
        
        /**
         * ---------------------------------------------------------------------
         * AUTHORIZE ORDER
         * ---------------------------------------------------------------------
         */
//         $order = $this->getServiceLocator()->get('Openy\OrderMapper')->fetch(4);
        
        $order = $this->orderService->authorizeOrder($order);
        
//         print_r($order);        
//         die("kaka");
        

        /**
         * ---------------------------------------------------------------------
         * DELIVER ORDER
         * ---------------------------------------------------------------------
         */
//         $order = $this->getServiceLocator()->get('Openy\OrderMapper')->fetch(14);
            $sumary = array (
                'data'		=> 'SERIALIZED DATA',
                'details' 	=> array(
                        "Fecha"		=> "02/02/2015 18:00",
                        "Precio/lt"	=> "1,190",
                        "Litros" 	=> "25,23",
                        "Precio" 	=> "22,31€",
                        "IVA" 		=> "4,69€",
                        "Total"		=> "27€",
                        "Ahorro" 	=> "1.14"
                    ),
            );
//         $order = $this->getServiceLocator()->get('Openy\OrderMapper')->fetch(4);
        $order->summary = $sumary;
        $order->amount = 17.34;
        $order->deliverycode = 't14';
        $order = $this->orderService->deliverOrder($order);
//         print_r($order);
        
//         die("kaka");
        
        /**
         * ---------------------------------------------------------------------
         * PAY ORDER
         * ---------------------------------------------------------------------
         */
//         $order = $this->getServiceLocator()->get('Openy\OrderMapper')->fetch(4);
        
        $order = $this->orderService->payOrder($order);
//         print_r($order);
        
//         die("kaka");
        
        
        /**
         * ---------------------------------------------------------------------
         * RECEIPT ORDER
         * ---------------------------------------------------------------------
         */
//         $order = $this->getServiceLocator()->get('Openy\OrderMapper')->fetch(4);
        
        $order = $this->orderService->receiptOrder($order);
//         print_r($order);
        
//         die("kaka");
        
        
        
        
        /**
         * ---------------------------------------------------------------------
         * END ORDER
         * ---------------------------------------------------------------------
         */
        
        print_r($order);
        die("kaka");

        
    }
    
    
    
    
}
