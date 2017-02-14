#Order

## Order status

	STATUS_NOT_EXISTING = 0; 	// Order does not exist
	STATUS_CANCELLED = 1; 		// Cancelled by user
	STATUS_ORDERED = 2; 		// Order is registered in Openy
	STATUS_AUTHORIZED = 3; 		// Authorized by Openy to be delivered/served
									// e.g. User have enough bank account balance
									// e.g.2 User have enough Openy vouchers
	STATUS_ISSUED = 4; 			// Order has been issued for delivery
	STATUS_DELIVERED = 4; 		// Order has been delivered
	STATUS_PAYED = 5;
	STATUS_INVOICED = 6;
 


### Order entity register

#### Code 	
	
	$order = new OrderEntity();        
    $order->amount = $data->amount;
    $order->summary = array();                         
    $order->idopystation = $data->idopystation;
    $order = $this->orderService->registerOrder($order); 
	
#### Result

	Openy\Model\Order\OrderEntity Object
	(
	    [idorder] => 4
	    [idopystation] => 1
	    [summary] => Array
	        (
	        )
	
	    [amount] => 20.00
	    [iduser] => f7914e2b-b903-57cb-9b37-2063ea0ed1c8
	    [idpayment] => 
	    [paymentmethod] => 1
	    [deliverycode] => 
	    [orderstatus] => Openy\Model\Order\OrderStatusEntity Object
	        (
	            [status] => 2
	            [idorder] => 4
	            [paymentoperationid] => 
	            [lastresponse] => 
	            [lastcode] => 
	            [codemsg] => 
	            [openymsg] => 
	        )
	
	    [created] => 2015-09-10 12:10:33
	    [updated] => 
	)
	

	
	
	
	