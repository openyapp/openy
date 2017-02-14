<?php
namespace Openy\V1\Rest\Orders;

use Openy\Model\Hydrator\ReceiptHydrator;
use Openy\Model\Order\OrderEntity;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class OrdersResource extends AbstractResourceListener
{

    protected $orderService;
    protected $mapper;

    public function __construct($service, $mapper){
        $this->orderService = $service;
        $this->mapper = $mapper;
    }

    private function getOrderInstance($amount=null){
        $order = new OrderEntity();
        $order->iduser = '1f877fa9-1c73-56fb-bcf0-373a25b4e66a';
        $order->amount = 70.00;
        $summary = array (
                    'data'      => "SERIALIZED DATA",
                    'details'   => array(
                        "Fecha"     => "02/02/2015 18:00",
                        "Precio/lt" => "1,190",
                        "Litros"    => "25,23",
                        "Precio"    => "22,31€",
                        "IVA"       => "4,69€",
                        "Total"     => "27€",
                        "Ahorro"    => "1.14"
                    ));
        $order->summary = $summary;
        $order->idopystation = 1;
        return $order;
    }
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($orderService)
    {
        return new ApiProblem(405, 'The POST method has not been defined');
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $order = $this->getOrderInstance();
        $order = $this->orderService->prepareOrder($order);
        $order = $this->cancelOrder($order);
        return $order;
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        $order = $this->getOrderInstance();
        $order = $this->orderService->prepareOrder($order);
        return $order;
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        return $this->mapper->fetchAll($params);
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        // TEST order service
        $order = $this->getOrderInstance();
        $order = $this->orderService->prepareOrder($order);
        $order = $this->orderService->cancelOrder($order);
        return $order;
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        $order = $this->getOrderInstance();
    
        $order = $this->orderService->registerOrder($order);
        //        print("hem registrat l'order\n");
    
        $order = $this->orderService->authorizeOrder($order);
    
        // Reducimos el amount porque el número de litros consumidos ha sido inferior al solicitado
        $order->amount = 66.66;
        // Modificamos por completo el summary para ver que efectivamente queda persistido en BBDD
        $order->summary = array (
            'data'      => "SERIALIZED DATA",
            'details'   => array(
                "Fecha"     => "02/02/2015 18:00",
                "Precio/lt" => "1.190",
                "Litros"    => "25.23",
                "Precio"    => "22.31€",
                "Total"     => "27€",
                "Ahorro"    => "1.14",
                "IVA"       => "21%",
                "IVAAmount"=> "4.59€",
                "Product"   => "GOA",
            ));
        // Código de ticket de aadapter para este consumo (no se podrá usar 2 veces, pues es UNIQUE)
        $order->deliverycode = hash('md5',microtime());
        // Informamos a Openy de que se ha hecho efectiva la entrega de este pedido
        $order = $this->orderService->deliverOrder($order);
    
    
        // En caso que el parámetro de la api sea un número impar procedemos a pagar ya recoger el ticket
        if ($id % 2){
    
    
            $order = $this->orderService->payOrder($order);
    
    
            $receipt = $this->orderService->receiptOrder($order);
    
            /*            $order = $this->mapper->fetch(396);
             $invoice = $this->orderService->invoiceOrder($order);
             /*            $invoiceService = $this->orderService->getPaymentService()->getInvoiceService();
             $invoice = $invoiceService->getInvoice(new \Openy\Model\Payment\ReceiptCollection([$receipt]));
    
            $receipt = $invoice;*/
    
        }
        return (isset($invoice) ? $invoice : ((isset($receipt) && ((bool)$receipt))? $receipt : $order));
    
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
    
    public function update2($id, $data)
    {
        $order = $this->getOrderInstance();
        $order = $this->orderService->registerOrder($order);
        $order = $this->orderService->authorizeOrder($order);
	// Reducimos el amount porque el número de litros consumidos ha sido inferior al solicitado
        $order->amount = 26.45;
	// Modificamos por completo el summary para ver que efectivamente queda persistido en BBDD
        $order->summary = array (
                        'data'      => "SERIALIZED DATA",
                        'details'   => array(
                                        "Fecha"     => "08/09/2015 12:00",
                                        "Precio/lt" => "1,190",
                                        "Litros"    => "22,23",
                                        "Precio"    => "21,86€",
                                        "IVA"       => "4,59€",
                                        "Total"     => "26.45€",
                                        "Ahorro"    => "1.14f"
                                    ));
	// Código de ticket de aadapter para este consumo (no se podrá usar 2 veces, pues es UNIQUE)
        $order->deliverycode = 'boyer ha muerto y ruizma también';
	// Informamos a Openy de que se ha hecho efectiva la entrega de este pedido
        $order = $this->orderService->deliverOrder($order);
	// En caso que el parámetro de la api sea un número impar procedemos a pagar ya recoger el ticket
        if ($id % 2){
            $order = $this->orderService->payOrder($order);
            $receipt = $this->orderService->receiptOrder($order);
        }
        return isset($receipt) ? $receipt : $order;

        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
