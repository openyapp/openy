<?php

namespace App\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class IndexController extends AbstractActionController
{   
//     protected $eventIdentifier = 'NoSqliteauth';
    
    public function indexAction()
    {
        $view = new ViewModel(array('urlframe' => '/appframe/'));
        return $view;
        
        
    }
 
}