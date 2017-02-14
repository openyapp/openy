<?php
namespace Openy\Model\Hydrator\Strategy;

use Openy\Model\Hydrator\Strategy\UnsignedIntZeroPadStrategy;

class PanStrategy extends UnsignedIntZeroPadStrategy
{
	const PAN_LENGTH = 4;

	protected $pan_length;

	public function __construct($pan_length = self::PAN_LENGTH){
		$this->pan_length = $pan_length;
		return parent::__construct($pad_length = self::PAN_LENGTH, $padding_behaviour = self::PAD_WHEN_HYDRATING);
	}


	public function extract($value){
		$value = substr(trim($value),-4);
		return parent::extract($value);
	}


	public function hydrate($value){
		$value = substr(trim($value),-4);
		return parent::hydrate($value);
	}

}
