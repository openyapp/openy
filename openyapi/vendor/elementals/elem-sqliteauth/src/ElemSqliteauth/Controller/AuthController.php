<?php
namespace ElemSqliteauth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;

use ElemSqliteauth\Entity\User;

class AuthController extends AbstractActionController
{
    protected $form;
    protected $storage;
    protected $authservice;
    protected $eventIdentifier = 'NoSqliteauth';
     
    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
            ->get('ElemSqliteauth\Service');
        }
         
        return $this->authservice;
    }
     
    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
            ->get('ElemSqliteauth\Authentication\Storage\StorageAuth');
        }
         
        return $this->storage;
    }
     
    public function getForm()
    {
        if (! $this->form) {
            $user       = new User();
            $builder    = new AnnotationBuilder();
            $this->form = $builder->createForm($user);
        }
         
        return $this->form;
    }
     
    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('success');
        }
         
        $form       = $this->getForm();
         
        return array(
            'form'      => $form,
            'messages'  => $this->flashmessenger()->getMessages()
        );
    }
     
    public function authenticateAction()
    {
        $form       = $this->getForm();
        $redirect = 'login';
         
        $request = $this->getRequest();
        
//         echo "<pre>";
//         print_r($request);
//         echo "</pre>";
        
        if ($request->isPost()){
            $form->setData($request->getPost());
            if ($form->isValid()){
                //check authentication...
                $this->getAuthService()->getAdapter()
                                       ->setIdentity($request->getPost('username'))
                                       ->setCredential(md5($request->getPost('password')));

                $result = $this->getAuthService()->authenticate();
                
                    
                foreach($result->getMessages() as $message)
                {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->setNamespace('default')->addMessage($message);
                }
                 
                if ($result->isValid()) {
                    $redirect = 'success';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->getSessionStorage()
                        ->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    
                    $result = $this->getAuthService()->authenticate();
                    
                    $result = $this->getAuthService()->getAdapter()->getResultRowObject();
//                     $usersrow  = $this->getUserTable()->getUserById($result->id);

                    
                    $this->getAuthService()->getStorage()->write(array(
                        'id' => $result->iduser,
                        'username' => $result->username,
                        'email' => $result->email,
                        'name' => $result->name,
                        'idrol' => $result->idrol
                        //other session key => value here.
                    ));
                    
                    
                    
//                     print_r($this->getAuthService()->getStorage()->read());
//                     die;
                    
//                     $this->getAuthService()->getStorage()->write($request->getPost('username'));
                }
            }
        }
         
        return $this->redirect()->toRoute($redirect);
    }
     
    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
         
        $this->flashmessenger()->setNamespace('success')->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }
}