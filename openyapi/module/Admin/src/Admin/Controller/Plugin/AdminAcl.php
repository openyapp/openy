<?php
//filename : module/ZfCommons/src/ZfCommons/Controller/Plugin/MyPlugin.php
namespace Admin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Session\Container as SessionContainer,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource;
 
class AdminAcl extends AbstractPlugin
{
    protected $sesscontainer ;

//     protected $dbAdapter;
//     public function __construct(\Zend\Db\Adapter\Adapter $dbAdapter)
//     {
//         $this->dbAdapter = $dbAdapter;
//     }
    
    private function getSessContainer()
    {
        if (!$this->sesscontainer) {
            $this->sesscontainer = new SessionContainer('adminacl');
        }
        return $this->sesscontainer;
    }
    
    public function doSomething()
    {
//         echo "----------------------kaka";
        return;
    }
     
    public function doAuthorization($e)
    {
        
        /*
         * Roles
         * 1 = developer
         * 2 = apidevel
         * 3 = viewer
         *
         */
        
        //setting ACL...
        $acl = new Acl();
        //add role ..
        $acl->addRole(new Role('viewer'));
        $acl->addRole(new Role('apidevel'),  'viewer');
        $acl->addRole(new Role('developer'), 'apidevel');
         
        $acl->addResource(new Resource('Application'));
        $acl->addResource(new Resource('ElemSqliteauth'));
        $acl->addResource(new Resource('Admin'));
        $acl->addResource(new Resource('Developer'));
         
        $acl->deny('viewer', 'Application', 'view');
        $acl->allow('viewer', 'ElemSqliteauth', 'view');
         
        $acl->allow('apidevel',array('Application'),array('view'));
         
        //admin is child of user, can publish, edit, and view too !
        $acl->allow('developer',array('Application'),array('publish', 'edit'));
         
        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $namespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
         
        $role = (! $this->getSessContainer()->role ) ? 'viewer' : $this->getSessContainer()->role;
        if ( ! $acl->isAllowed($role, $namespace, 'view'))
        {
            $router = $e->getRouter();
            $url    = $router->assemble(array(), array('name' => 'login'));
             
            $response = $e->getResponse();
            $response->setStatusCode(302);
            //redirect to login route...
            /* change with header('location: '.$url); if code below not working */
            $response->getHeaders()->addHeaderLine('Location', $url);
            $e->stopPropagation();
        }
    }
}