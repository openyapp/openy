<?php
namespace Openy\Model\Hydrator\Strategy;

class UnsignedIntZeroPadStrategy extends PadLeftStrategy
{

	const PAD_WHEN_HYDRATING = FALSE;
	const PAD_WHEN_EXTRACTING = TRUE;

	protected $padding_behaviour;

	public function __construct($pad_length, $padding_behaviour = self::PAD_WHEN_HYDRATING){
		$this->padding_behaviour = $padding_behaviour;
		return parent::__construct($pad_length,$pad_string = '0');
	}

	public function extract($value){
		if ($this->padding_behaviour == self::PAD_WHEN_HYDRATING)
			return parent::extract($value);
		else
			return parent::hydrate($value);
	}

	public function hydrate($value){
		if ($this->padding_behaviour == self::PAD_WHEN_HYDRATING)
			return parent::hydrate($value);
		else
			return parent::extract($value);
	}

}
