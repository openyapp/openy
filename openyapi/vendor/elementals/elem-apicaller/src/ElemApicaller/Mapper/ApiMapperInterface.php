<?php

namespace ElemApicaller\Mapper;

interface ApiMapperInterface
{
    public function fetchAll(array $filter);
    
    public function fetch($id);
    
    public function create(array $data);

    public function update($id, array $data);

    public function patch($id, array $data);
    
    public function delete($id); 
    
    public function setUrl($url);
    
    public function setResource($resource);
       
}
