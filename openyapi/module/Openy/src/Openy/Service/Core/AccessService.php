<?php

namespace Openy\Service\Core;

use Openy\Model\Core\AccessEntity as Access;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Openy\Interfaces\Service\Core\AccessInterface as AccessServiceInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AccessService
	implements 	AccessServiceInterface,
				ServiceLocatorAwareInterface
{
	use ServiceLocatorAwareTrait;

	public function getCurrentAccess(){
		$access = new Access();
		$data = get_object_vars($access);
        //TODO: Do following with Strategies rather than hardcoded
        $data['ip'] 	= $_SERVER['REMOTE_ADDR'];
        $data['client']	= $this->getCurrentUser()->getClient();
        $data['token']  = $this->getCurrentUser()->getBearer();
        //END TODO
        $data = (object)$data;
        return $this->getAccessMapper()->insert($data);
	}

	private function getCurrentUser(){
		return $this->getServiceLocator()->get('Oauthreg\Service\CurrentUser');
	}

	private function getAccessMapper(){
		return $this->getServiceLocator()->get('Openy\Mapper\Core\Access');
	}

}