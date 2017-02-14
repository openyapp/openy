<?php
namespace Openy\V1\Rpc\GetInvoice;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\JsonModel;
use ZF\ContentNegotiation\ViewModel;

use Openy\Model\Payment\ReceiptCollection;
use Openy\Model\Payment\ReceiptEntity;

class GetInvoiceController extends AbstractActionController
{
    protected $options;
    protected $invoiceService;
    
    public function __construct($options, $invoiceService)
    {
        $this->options          = $options;
        $this->invoiceService          = $invoiceService;
    }
    
    public function getInvoiceAction()
    {
        $view = new ViewModel();
        $view->setTerminal(true);
        
        $data = $this->bodyParams();
        $receipts = explode(',', $data['receipt']);
        
//         $receivers = $this->options->getEmailReceivers();
//         if(isset($data['receipt']])
//         {
//             $receipt = $receivers[$data['receipt']];
//         }
//         else {
//             return new ApiProblemResponse(
//                 new ApiProblem(
//                     400 ,
//                     'Receiver not recognized',
//                     'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
//                     'Bad Request'
//                 )
//             );
//         }
        
        
        
//         print_r($receipts);
        
        $data = $receipts;
        
        
        $entities = [];
        foreach($receipts as $id){
            $entities[] = new ReceiptEntity($id);
        }
        $receipts = new ReceiptCollection($entities);
        
        $result = $this->invoiceService->getInvoice($receipts);
        $receipts = $this->invoiceService->getReceipts($result);
        $result->receipts = $receipts->getEntities();
//         $result->receipts = new \Openy\V1\Rest\Receipt\ReceiptCollection($receipts->getAdapter());
        
        print_r($result);
        
//         print_r($receipts);
        
//         return $result;
        
        die;
        
        
    }
    
}
