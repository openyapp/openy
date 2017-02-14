<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Openy\Model\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\MetadataFeature as ZendMetadataFeature;

/*use Zend\Db\Metadata\Metadata;
use Zend\Db\Metadata\MetadataInterface;
use Zend\Db\TableGateway\Exception;
use Zend\Db\Metadata\Object\TableObject;*/

class MetadataFeature extends ZendMetadataFeature{

	public function __get($property){
		if ($property == 'metadata'){
			return $this->sharedData['metadata'];
		}
		else return null;
	}
}
