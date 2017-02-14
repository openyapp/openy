<?php

namespace App\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class AppController extends AbstractActionController
{   
    protected $eventIdentifier = 'NoSqliteauth';
    
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function faqAction()
    {
        return new ViewModel();
    }
    
    public function termsAction()
    {
        return new ViewModel();
    }
    
    public function refuelAction()
    {
        return new ViewModel();
    }
    public function privacyAction()
    {
        return new ViewModel();
    }
    public function trenAction()
    {
        return new ViewModel();
    }
    public function tren2Action()
    {
        return new ViewModel();
    }
 
}