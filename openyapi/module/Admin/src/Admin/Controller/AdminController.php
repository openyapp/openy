<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use RuntimeException;

class AdminController extends AbstractActionController
{   
//     protected $eventIdentifier = 'NoSqliteauth';
    
    public function getStationMapper()
    {
        $sm = $this->getServiceLocator();
        return $sm->get('Admin\Model\StationMapper');
    }
    public function getPriceMapper()
    {
        $sm = $this->getServiceLocator();
        return $sm->get('Admin\Model\PriceMapper');
    }
    
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function importAction()
    {
        
        $filename = $this->params()->fromRoute('file', 0);
        echo $filename;
//         die;
        $stationMapper = $this->getStationMapper();
        $priceMapper = $this->getPriceMapper();

        
        $directory = $_SERVER['DOCUMENT_ROOT'].'/../data/officialstations/es/json/latest';
        $country = 'ES';
//         $iterator = new \DirectoryIterator($directory);
//         while($iterator->valid()) 
//         {
//             if ($iterator->isFile())
            if ($filename)
            {
//                 echo $iterator->getFilename() . "\n";
                echo $filename . "\n";
                
                $fueltypes = array('BIE.json'=>9,
                    'BIO.json'=>7,
                    'G95.json'=>8,
                    'G98.json'=>2,
                    'GLP.json'=>10,
                    'GNC.json'=>11,
                    'GOA.json'=>3,
                    'GOB.json'=>5,
                    'GOC.json'=>6,
                    'GPR.json'=>1,
                    'NGO.json'=>4 
                );
//                 $content = file_get_contents($directory.'/'.$iterator->getFilename());
                $content = file_get_contents($directory.'/'.$filename);
                $content = json_decode($content);
                foreach($content as $key => $value)
                {
                    $result = $stationMapper->save($value);
//                     $price = array('idoffstation'=>$result,
//                                    'price'=>$value->price,
//                                    'idfueltype'=>$fueltypes[$iterator->getFilename()]
//                     );
//                     $result = $priceMapper->save($price);                    
                }
            }
//             $iterator->next();
//         }
        return array('country'=>$country);
    }
    
}
