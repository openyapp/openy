<?php
namespace Oauthreg\V1\Rpc\Revoke;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\JsonModel;

class RevokeController extends AbstractActionController
{
    protected $recoverPasswordMapper;
    protected $options;
    protected $currentUser;
    
    public function __construct($recoverPasswordMapper, $options, $currentUser)
    {
        $this->recoverPasswordMapper = $recoverPasswordMapper;
        $this->options = $options;
        $this->currentUser = $currentUser;
        
    }
    
    public function revokeAction()
    {
        $username = $this->currentUser->getUser('username');
        $result = $this->recoverPasswordMapper->revoke($username);
        
        return new JsonModel(array(
            'result' => $result,
        ));
    }
}
