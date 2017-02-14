<?php

namespace Openy\Interfaces;

interface MapperInterface{

	public function fetchAll($filters);
	public function fetch($id, $where = []);
	public function insert($data);
	public function replace($id,$data);
	public function update($id, $data);
	public function delete($id);
	public function locate($entity);
    public function exists(&$entity,$fetch_entity_if_exists = FALSE);
	//public function exists($entity);

}