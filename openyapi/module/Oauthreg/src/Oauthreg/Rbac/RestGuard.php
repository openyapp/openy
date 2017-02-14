<?php
namespace Oauthreg\Rbac;


/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
* "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
* A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
* OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
* SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
    * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
    * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
* THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
* OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
* This software consists of voluntary contributions made by many individuals
* and is licensed under the MIT license.
*/

// namespace ZfcRbac\Guard;

use ZfcRbac\Guard\AbstractGuard;
use ZfcRbac\Guard\ProtectionPolicyTrait;

use Zend\Mvc\MvcEvent;
use ZfcRbac\Service\RoleService;
use ZfcRbac\Guard\GuardInterface;
use ZfcRbac\Exception\InvalidArgumentException;
use ZF\MvcAuth\Authorization\DefaultResourceResolverListener;

use ZfcRbac\Service\AuthorizationServiceInterface;
use ZF\MvcAuth\Identity\AuthenticatedIdentity;
use ZF\MvcAuth\MvcAuthEvent;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

use Zend\Console\Request as ZendConsole;

/**
 * A controller guard can protect a controller and a set of actions
 *
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 * @licence MIT
 */
class RestGuard extends AbstractGuard
{
    use ProtectionPolicyTrait;

    /**
     * Event priority
     */
    const EVENT_PRIORITY = -51;

    /**
     * @var RoleService
     */
//     protected $roleService;
    protected $authorizationService;
    protected $resourceResolver;

    /**
     * Controller guard rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Constructor
     *
     * @param AuthorizationService  $authorizationService
     * @param array                 $rules
    */
    public function __construct(AuthorizationServiceInterface $authorizationService, array $rules = [])
    {
        /* @var ZfcRbac\Service\AuthorizationService; $authenticationProvider */
        $this->authorizationService = $authorizationService;
        $this->setRules($rules);
    }

    
    public function setResourceResolver(DefaultResourceResolverListener $resourceResolver)
    {
        $this->resourceResolver = $resourceResolver;        
    }
   
    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }
    
    public function isGranted(MvcEvent $event)
    {
        $matchedRouteName = $event->getRouteMatch()->getMatchedRouteName();
        
        $allowedPermissions = null;
        $router = $event->getRouteMatch();
        $matchedRoute = $router->getParam('controller');
        $request = $event->getRequest();
        $role = $this->authorizationService->getIdentity();
        $resource = $this->resourceResolver->buildResourceString($router, $request);
        
        if($request instanceof ZendConsole)
        {
//                    echo "router: "."\n";
//                    echo get_class($request);
//                    echo "\n";
                   
                   return true;
        }
        $privilege = $request->getMethod();
//                 echo "router: "."\n";
//                 print_r($router->getParam('controller'));
//                 echo "\n";
        
//                 echo "matchedRouteName: "."\n";
//                 print_r($matchedRouteName);
//                 echo "\n";
        
//                 echo "role: "."\n";
//                 print_r($role);
//                 echo "\n";
                
//                 echo "resource: "."\n";
//                 print_r($resource);
//                 echo "\n";
                
//                 echo "privilege: "."\n";
//                 print_r($privilege);
//                 echo "\n";
        
//                 echo "rules: "."\n";
//                 print_r($this->rules);
//                 echo "\n";
        
        
        foreach (array_keys($this->rules) as $routeRule) 
        {
//             echo "routeRule: "."\n";
//             print_r($routeRule);
//             echo "\n";
            
//             echo "matchedRouteName: "."\n";
//             print_r($matchedRoute);
//             echo "\n";
            
            $matchedRouteName = $matchedRoute;
//             $match = fnmatch($routeRule, $matchedRouteName, FNM_CASEFOLD);
            
//             echo "match: "."\n";
//             var_dump($match);
//             echo "\n";
            
            if ($routeRule === $matchedRouteName)
            {
//                 echo "-----match-----";
                $allowedPermissions = $this->rules[$routeRule];
                break;
            }
            
        }
        
//         echo "allowedPermissions: "."\n";
//         var_dump($allowedPermissions);
//         echo "\n";
    
//         echo "protectionPolicy: "."\n";
//         var_dump($this->protectionPolicy);
//         echo "\n";
        
        // If no rules apply, it is considered as granted or not based on the protection policy
        if (null === $allowedPermissions) {
            return $this->protectionPolicy === self::POLICY_ALLOW;
        }
//     echo "------ssssss----";
        if (in_array('*', $allowedPermissions)) {
            return true;
        }
    
        $restGuard = $this->rules;
        list($controller, $group) = explode('::', $resource);
        

//                 echo "restGuard: "."\n";
//                 var_dump($restGuard[$controller][$group][$privilege]);
//                 echo "\n";

//                 echo "controller: "."\n";
//                 print_r($controller);
//                 echo "\n";
                
//                 echo "group: "."\n";
//                 print_r($group);
//                 echo "\n";
                
//                 echo "privilege: "."\n";
//                 print_r($privilege);
//                 echo "\n";
                
        if (isset($restGuard[$controller][$group][$privilege])) 
        {
            $result = $restGuard[$controller][$group][$privilege];
            if (is_array($result)) 
            {
                $and = true;
                foreach ($result as $r) 
                {
//                     echo "r: "."\n";
//                     print_r($r);
//                     echo "\n";
                    $and = $and && $this->authorizationService->isGranted($r);
                }
                $result = $and;
            }
            
//             echo "result: "."\n";
//             print_r($result);
//             echo "\n";
            
           
            
                
            return $result;
        }
        
        return true;
        
    }    
    
}
