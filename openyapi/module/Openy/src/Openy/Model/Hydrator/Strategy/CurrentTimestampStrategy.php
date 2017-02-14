<?php
/**
 * CurrentTimestamp Strategy.
 * Hydrator Strategy for Timestamp (and Datetime) fields
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core\Hydration
 *
 */
namespace Openy\Model\Hydrator\Strategy;


use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;
use Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;
use \DateTime;


/**
 * CurrentTimestamp Strategy.
 *
 * Replaces any non time value with Current time.
 * Returns always a formatted string representation of a DateTime value.
 *
 * @uses http://framework.zend.com/apidoc/2.4/classes/Zend.Stdlib.Hydrator.Strategy.DateTimeFormatterStrategy.html Formatter for DateTime Strategies
 * @see http://framework.zend.com/apidoc/2.4/classes/Zend.Stdlib.Hydrator.Strategy.DefaultStrategy.html Zend Default Hydrator Strategy
 *
 */
class CurrentTimestampStrategy extends DefaultStrategy
{
	/**
	 * Inner strategy used for formatting DateTime attributes
	 * @var DateTimeFormatterStrategy
	 * @internal
	 */
	protected $DatetimeStrategy;

	/**
	 * Format to be used when formatting with $DatetimeStrategy
	 * @var String Format used for formatting values
	 * @link http://php.net/manual/en/function.date.php Allowed formats for date function at php.net
	 */
	protected $format;

	/**
	 * Constructor
	 *
	 * Saves given format and timezone to apply to DateTime attributes
	 * @param string            $format   Format to be applied when hydrating or extracting
	 * @param \DateTimeZone $timezone Timezone (applies when formatting datetime values)
	 */
	public function __construct($format = 'Y-m-d H:i:s', \DateTimeZone $timezone = null){
		$this->DatetimeStrategy = new DateTimeFormatterStrategy($format, $timezone);
		$this->format = $format;
	}

	/**
	 * GET Format
	 *
	 * Returns format applied by Strategy instance
	 * @return String Stored format to be applied
	 */
	public function getFormat(){
		return $this->format;
	}

	/**
	 * Extract
	 *
	 * Returns a DateTime value formatted with stored $format
	 * @see  CurrentTimestampStrategy::__construct() Constructor method
	 * @param  \DateTime|NULL $value Value to be formatted
	 * @return String        Datetime string representation of given value or current time
	 */
	public function extract($value){
		if (is_null($value) or (FALSE === strtotime($value)))
			$value = new DateTime();
		//OPTION 1
		//return $this->DatetimeStrategy->extract($value);

		//OPTION 2
		if ($value instanceof DateTime)
			$value = $value->format($this->format);

		return parent::extract($value);
	}

	/**
	 * Hydrate
	 *
	 * Does the same as extract()
	 * @see  CurrentTimestampStrategy::extract() Extract method
	 * @param  \DateTime|NULL $value Value to be formatted
	 * @return String        Datetime string representation of given value or current time
	 */
	public function hydrate($value){
		if (is_null($value) or (FALSE === strtotime($value)))
			$value = new DateTime();
		//OPTION 1
		//return $this->DatetimeStrategy->hydrate($value);

		//OPTION 2
		if ($value instanceof DateTime)
			$value = $value->format($this->format);
		return parent::hydrate($value);
	}

}
