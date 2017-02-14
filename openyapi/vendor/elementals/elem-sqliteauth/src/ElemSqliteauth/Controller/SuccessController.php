<?php
namespace ElemSqliteauth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SuccessController extends AbstractActionController
{
    public function indexAction()
    {
        
        if ($this->getServiceLocator()->get('ElemSqliteauth\Service')->hasIdentity()) {
            $users = $this->getServiceLocator()->get('ElemSqliteauth\Service')->getStorage()->read();
            //print_r($users);
        }
        
        
        if (! $this->getServiceLocator()->get('ElemSqliteauth\Service')->hasIdentity())
        {
            return $this->redirect()->toRoute('login');
        }
         
        return new ViewModel(array(
                                    'users'      => $users,
                                    'messages'  => $this->flashmessenger()->getMessages()
                                ));
    }
}