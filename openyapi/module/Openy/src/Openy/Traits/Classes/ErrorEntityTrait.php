<?php

namespace Openy\Traits\Classes;

use Openy\Model\Classes\MessageEntity as Message;


trait ErrorEntityTrait{

	/**
	 * Status Message used for reporting errors
	 * @var [type]
	 */
	public $errors = [];

	public function getErrors(){
		return $this->errors;
	}

	public function addError(Message $error){
		$this->errors[] = $error;
		return $this;
	}

	public function clearErrors(){
		$this->errors = [];
		return $this;
	}

	public function setErrors($errors){
		$errors = (array)$errors;
		$this->errors = $errors;
		return $this;
	}

	public function hasErrors(){
		return (0 < count($this->errors));
	}

	public function inErrors($code){
		$result = false;
		foreach ($this->errors as $error){
			$result = ($error->code == $code);
			if ($result) break;
		}
		return $result;
	}

	public function __set($property,$value){
		if ($property=='errors')
			$this->errors = $value;
		else if (method_exists(get_parent_class($this), '__set'))
			return parent::__set($property,$value);
		return $this;
	}

	public function __get($property){
		if ($property=='errors')
			return $this->errors;
		else if (method_exists(get_parent_class($this), '__get'))
			return parent::__get($property);
		else
			return null;
	}

}