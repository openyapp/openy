<?php

namespace Openy\Model\Classes;
use Openy\Options\SubOptions;

class MessageEntity
	implements \ArrayAccess
{
	/**
	 * Default message text
	 * @var String
	 */
	public $text;

	/**
	 * Message code identifier
	 * @var string
	 */
	public $code;

	/**
	 * Text property translations to multiple languages
	 * @var Array
	 */
	public $translations = [];

	public function __construct(SubOptions $options = null, $code = 'default'){
		$this->code = $code;
		if (!is_null($options)){
			if (isset($options['messages']) && isset($options['messages'][$code]))
				$options = $options['messages'][$code];

			if (isset($options['text']))
				$this->text = $options['text'];
			if (isset($options['translations'])){
				$this->translations = $options['translations']->toArray();
			}
		}
	}


	public function __call($function,$args){
		$function = '__'.$function;
		if (method_exists($this, $function))
			return call_user_func_array(array($this,$function),$args);
		return null;
	}

	protected function __text($lang="default"){
		$translations = array_merge([$lang=>$this->text],$this->translations);
		return $translations[$lang];
	}

	public function offsetSet($offset, $valor) {
		if ($offset == 'default' || is_null($offset)){
			$this->text = $valor;
		}else {
            $this->translations[$offset] = $valor;
        }
    }

    public function offsetExists($offset) {
    	if(is_null($offset))
    		return true;
    	else
        	return isset($this->translations[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->translations[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->translations[$offset]) && $offset!='defualt' ? $this->translations[$offset] : $this->text;
    }


}