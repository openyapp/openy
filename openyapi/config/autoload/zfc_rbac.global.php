<?php
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

/**
 * Copy-paste this file to your config/autoload folder (don't forget to remove the .dist extension!)
 */

return array(
    'zfc_rbac' => array(
        'identity_provider'   => 'Oauthreg\\Rbac\\IdentityProvider',
        'guest_role' => 'guest',
//         'guards' => array(),
        'protection_policy' => \ZfcRbac\Guard\GuardInterface::POLICY_ALLOW,
//         'protection_policy' => \ZfcRbac\Guard\GuardInterface::POLICY_DENY,
        
        // three roles: admin, user and guest.
        'role_provider' => array(
            'ZfcRbac\Role\InMemoryRoleProvider' =>  array(
                'admin' =>  array(
                    'children'  =>  array('user'),
                    'permissions'   =>  array(
                        'readAll',
                    ),
                ),
                'user' =>  array(
                    'children'  =>  array('guest'),
                    'permissions'   =>  array(
                        'simple',
                    ),
                ),
                'guest' =>  array(),
            ),
        ),
//         'rest_guard' => array(
//             'Oauthreg\\V1\\Rest\\Clientregister\\Controller' => array (
//                 'entity' => array(
//                     'GET'    => true,               // everyone can use GET /foo/:id
//                     'POST'   => false,              // nobody can use POST /foo/:id
//                     'PATCH'  => array('canDoFoo'),  // only admin or user can use PATCH /foo/:id
//                     'PUT'    => array('canDoFoo', 'canDoBar'), // only roles that have BOTH permissions (admin/user) can use PUT /foo/:id
//                     'DELETE' => array('canDoFoo'),
//                 ),
//                 'collection' => array (
//                     'GET'    => true,               // everyone can use GET /foo
//                     'POST'   => array('canDoFoo'),  // only admin or user can use POST /foo
//                     'PATCH'  => false,              // nobody can use PATCH /foo
//                     'PUT'    => false,
//                     'DELETE' => array('canDoBaz'),  // only admin can use DELETE /foo
//                 ),
//             ),
//             'Openy\\V1\\Rest\\Fueltype\\Controller' => array (
//                 'entity' => array(
//                     'GET'    => true,
//                     'POST'   => false,
//                     'PATCH'  => array('canDoFoo'),
//                     'PUT'    => array('canDoFoo', 'canDoBar'),
//                     'DELETE' => array('canDoFoo'),
//                 ),
//                 'collection' => array (
//                     'GET'    => array('canDoBaz'),
//                     'POST'   => array('canDoFoo'),
//                     'PATCH'  => false,
//                     'PUT'    => false,
//                     'DELETE' => array('canDoBaz'),
//                 ),
//             ),
//         ),
        'guard_manager' => [
            'factories' => [
                'Oauthreg\Rbac\RestGuard' => 'Oauthreg\Rbac\RestGuardFactory'
            ]
        ],
        'guards' =>array(
            'Oauthreg\Rbac\RestGuard' => array(
         /*          'Oauthreg\\V1\\Rest\\Clientregister\\Controller' => array (
                    'entity' => array(
                        'GET'    => true,               
                        'POST'   => false,              
                        'PATCH'  => false,  
                        'PUT'    => false, 
                        'DELETE' => false,
                    ),
                    'collection' => array (
                        'GET'    => array('readAll'),  
                        'POST'   => true,  
                        'PATCH'  => false,              
                        'PUT'    => false,
                        'DELETE' => false,  
                    ),
                ),*/
//                 'Openy\\V1\\Rest\\Fueltype\\Controller' => array (
//                     'entity' => array(
//                         'GET'    => true,              
//                         'POST'   => false,             
//                         'PATCH'  => array('canDoFoo'), 
//                         'PUT'    => array('canDoFoo', 'canDoBar'), 
//                         'DELETE' => array('canDoFoo'),
//                     ),
//                     'collection' => array (
//                         'GET'    => array('canDoBaz'),
//                         'POST'   => array('canDoFoo'),
//                         'PATCH'  => false,            
//                         'PUT'    => false,
//                         'DELETE' => array('canDoBaz'),
//                     ),
//                 ),
            ),
        ),
    )
);
