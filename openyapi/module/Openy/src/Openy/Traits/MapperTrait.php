<?php

namespace Openy\Traits;

use Openy\Interfaces\MapperInterface;

trait MapperTrait{

    protected $mapper;

    public function setMapper(MapperInterface $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        return $this->mapper;
    }
}