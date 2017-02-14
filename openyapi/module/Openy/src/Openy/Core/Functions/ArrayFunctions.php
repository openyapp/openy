<?php

namespace Openy\Core\Functions;

final class ArrayFunctions{

	/**
	 *
	 * Tells whenever an array is associative or not.
	 * strict argument forces to assert that all keys are associative.
	 * @param array $array 		The array to check
	 * @param bool $__strict__  Says if all keys must be string to consider the array associative (TRUE) or not (FALSE)
	 * @return bool  			TRUE if array contains string keys
	 */
	static function is_assoc($array,$__strict__ = true) {
		$array = (array)$array;
		if ($__strict__)
			return (count(array_filter(array_keys($array), 'is_string')) === count($array));
		else
			return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

	static function in_array($needle,$haystack){
		if (is_scalar($needle) && is_scalar($haystack))
			return in_array($needle,(array) $haystack);
		elseif (is_array($needle) && is_scalar($haystack))
			return FALSE;
		elseif (is_array($needle) && is_array($haystack)){
			$i = 0;
			$result = FALSE;
			while(($i < count($haystack)) && !$result){
				$comparison = self::compare_vars($needle,$haystack[$i]);
				$result =($comparison == 0);
				$i++;
			}
			return $result;
		}
	}

	static function array_intersect($a,$b){
		$result = [];
		foreach($a as $value)
			if (self::in_array($value,$b)) $result[]=$value;
		return $result;
	}

	static function compare_vars($a,$b){
		$result = self::compare_types($a,$b);
		// both have same type
		if ($result == 0){
			if (!is_scalar($a)){
				$a = (array)$a;
				$b = (array)$b;
				$result = self::compare_counts($a,$b);
				if ($result == 0){
					$c = array_diff($a, $b);
					$d = array_diff($b, $a);
					$result = self::compare_counts($c,$d);
					if (($result == 0) && count($c) && count($d)){
						sort($c);
						sort($d);
						return self::compare_vars(reset($c),reset($d));
					}
				}
			}
		}
		return $result;
	}


	static function compare_types($a,$b){
		// An array is considered bigger than a scalar value
		if (is_scalar($a) && is_array($b))
			return -1;
		elseif (is_array($a) && is_scalar($b))
			return 1;
		elseif (is_scalar($a) && is_scalar($b)){
			return self::compare_scalars($a,$b);
		}
		else return 0;
	}

	static function compare_scalars($a,$b){
		if ($a > $b)
			return 1;
		if ($b > $a)
			return -1;
		if ($b == $a)
			return 0;
	}

	static function compare_counts($a,$b){
		if (count($a)>count($b))
			return 1;
		elseif (count($b)>count($a))
			return -1;
		else
			return 0;
	}
}



