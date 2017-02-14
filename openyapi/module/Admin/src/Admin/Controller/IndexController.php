<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class IndexController extends AbstractActionController
{   
//     protected $eventIdentifier = 'NoSqliteauth';
    
    public function indexAction()
    {
        return new ViewModel();
    }
 
}