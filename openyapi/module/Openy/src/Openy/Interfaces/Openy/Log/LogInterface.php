<?php
/**
 * Interface.
 * Log Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core\Log
 * @category Log
 *
 */
namespace Openy\Interfaces\Openy\Log;

/**
 * LogInterface.
 * Defines methods for logging messages of levels:
 * * debug
 * * info
 * * notice
 * * warning
 * * error
 * * critical
 * * alert
 * * emergency 
 *
 * @see \Zend\Log\Logger Zend Logger
 */
interface LogInterface
{
	/**
	 * Logs an EMERG(ENCY) level message
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
	public function emerg($message, $extra = array());
	
	/**
	 * Logs an ALERT level message
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
	public function alert($message, $extra = array());
	
	/**
 	 * Logs a CRIT(ICAL) level message 
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
	public function crit($message, $extra = array());
	
	/**
 	 * Logs an ERROR level message 
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
	public function err($message, $extra = array());
	
	/**
 	 * Logs a WARN(ing) level message 
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
	public function warn($message, $extra = array());
	
	/**
 	 * Logs a NOTICE level message 
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
	public function notice($message, $extra = array());
	
	/**
 	 * Logs an INFO level message  
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
	public function info($message, $extra = array());
	
	/**
 	 * Logs a DEBUG level message 
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
	public function debug($message, $extra = array());
}