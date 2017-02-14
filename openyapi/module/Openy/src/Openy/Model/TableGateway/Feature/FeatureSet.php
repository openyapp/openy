<?php
namespace Openy\Model\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\FeatureSet as ZendFeatureSet;
use Openy\Model\TableGateway\Feature\MetadataFeature;

class FeatureSet extends ZendFeatureSet
{

	protected function getMetadataFeature(){
		foreach ($this->features as $potentialFeature)
           	if ($potentialFeature instanceof MetadataFeature)
           		return $potentialFeature;

		return false;       
	}

	/**
     * @param string $property
     * @return bool
     */
    public function canCallMagicGet($property)
    {
    	if ($property == 'metadata'){
    		$result = $this->getMetadataFeature();    	    
            return ($result!==FALSE);
    	}
    	else return parent::canCallMagicGet($property);        
    }

    /**
     * @param string $property
     * @return mixed
     */
    public function callMagicGet($property)
    {        
    	if ($property == 'metadata'){
    		$metadataFeature = $this->getMetadataFeature();            
    		return $metadataFeature->metadata;
    	}
    	else
    		return parent::callMagicGet($property);
    }

}