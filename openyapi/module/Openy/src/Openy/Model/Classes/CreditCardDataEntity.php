<?php

namespace Openy\Model\Classes;

use Openy\Traits\Classes\CreditCardDataTrait;
use Openy\Interfaces\Classes\CreditCardDataInterface;

class CreditCardDataEntity
	implements CreditCardDataInterface
{
	use CreditCardDataTrait;

	public function __construct($data = null){
		if (!is_object($data) && !is_array($data)) return;

		if (is_object($data))
			$data = get_object_vars($data);

		foreach(array_keys(get_object_vars($this)) as $prop):
			if(array_key_exists($prop, $data))
				$this->$prop = $data[$prop];
		endforeach;
	}

}