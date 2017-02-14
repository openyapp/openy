<?php

namespace Openy\Options;

use \Traversable;
use \ArrayAccess;
use Zend\Stdlib\AbstractOptions;

class SubOptions
	extends AbstractOptions
	implements ArrayAccess
{

	protected $options;

	public function __construct($options){
		if ($options instanceof AbstractOptions)
			$this->options = $options->toArray();
		elseif (is_object($options))
			$this->options = get_object_vars($options);
		elseif (is_array($options))
			$this->options = $options;
	}

	/**
	 * Returns object value
	 * @return [type] [description]
	 */
	public function get(){
		return $this->options;
	}

	public function value(){
		return $this->options;
	}

	public function options(){
		return $this->options;
	}

    public function toArray(){
    	$result = parent::toArray();
    	if (is_array($result) && array_key_exists('options', $result))
    		$result = $result['options'];
    	return $result;
    }


	public function __call($function,$args){
		$function = strtolower($function);

		if ((!is_array($this->options)) || empty($this->options))
		{
			switch($function):
				case 'getoptions' : return $this->options();
					break;
				case 'getvalue' : return $this->value();
					break;
				default:
					return null;
				break;
			endswitch;
		}

		$options = array_combine(
						array_map(
							function($key){
								return 'get'.strtolower($key);
							},
							array_keys($this->options)
						),
						array_keys($this->options)
					);

		$result = array_key_exists($function,$options) ? $this->options[$options[$function]] : null;

		if (count($args)){
			if (is_array($result) && array_key_exists(reset($args),$result))
				$result = $result[reset($args)];
			elseif(is_object($result) && property_exists($result, reset($args)))
				$result = $result->{reset($args)};
		}



		if (is_array($result) || $result instanceof Traversable || is_object($result))
			return new SubOptions($result);
		else{
			global $$result;
			if (function_exists($result))
				return call_user_func_array($result,$args);
			elseif (is_callable($$result))
				return call_user_func_array($$result,$args);
			return $result;
		}
	}


	public function offsetExists( $offset ){
		return array_key_exists($offset, (array)$this->options);
	}
	public function offsetGet ( $offset ){
		return $this->__call('get'.$offset,null);
	}

	public function offsetSet ( $offset , $value ){
		return $this;
	}

	public function offsetUnset ( $offset ){
		return $this;
	}

}