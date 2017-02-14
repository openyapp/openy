<?php
namespace ElemApicaller\Options;

use Zend\Stdlib\AbstractOptions;

abstract class AbstractApiCallerMapperOptions extends AbstractOptions implements ApiCallerMapperOptionsInterface
{

    protected $entityClass;
    
    protected $collectionClass;
    
	/**
     * @return the $entityClass
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

	/**
     * @param field_type $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }
	/**
     * @return the $collectionClass
     */
    public function getCollectionClass()
    {
        return $this->collectionClass;
    }

	/**
     * @param field_type $collectionClass
     */
    public function setCollectionClass($collectionClass)
    {
        $this->collectionClass = $collectionClass;
    }


    
    
}