<?php
/**
 * Trait.
 * Log Trait
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core\Log
 * @category Log
 *
 */
namespace Openy\Traits\Openy\Log;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * LogInterface.
 * Implements Openy Log Interface
 * @see Openy\Interfaces\Openy\Log\LogInterface Openy Log Interface
 * @uses \Zend\Log\Logger Zend Logger
 * @uses \Zend\Log\Writer\Stream Zend Log Stream Writer
 * 
 */

trait LogTrait
{

    protected $logfile;
    protected $logger;

    /**
     * Creates an stream pointing to $filename
     * @param  [type] $filename [description]
     * @return [type]           [description]
     */
    protected function getLogfile($filename = null){
        $filename = 'out.log';
        if (!isset($this->logfile) || !isnull($filename)){
            $filename = ($filename ? 'data/logs/'.basename($filename) : 'data/logs/'. basename(tempnam('/tmp',date('Ymd-H.i.s').'--')).'.log');
            $this->logfile = preg_replace('/\/$/','',getcwd()).'/'.$filename;
        }
        if (!file_exists($this->logfile)){
            $res = fopen($this->logfile,'x');
            fclose($res);
        }
        return $this->logfile;
    }

    protected function getLogger(){
        if (!isset($this->logger)){
            $this->logger = new Logger;
        }
        return $this->logger;
    }

    protected function setLogger(Logger &$logger){
        if (isset($this->logger)){
            unset($this->logger);
        }
        $this->logger = $logger;
    }

    protected function log($priority, $message, $extra = array(), $filename = null){
        if (count($this->getLogger()->getWriters())==0){
            $this->getLogger()->addWriter(new Stream($this->getLogfile($filename)));
        }
        $this->getLogger()->log($priority, $message, $extra);
    }


    /**
	 * Logs an EMERG(ENCY) level message
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
    public function emerg($message, $extra = array())
    {
        return $this->log(Logger::EMERG, $message, $extra);
    }

    /**
	 * Logs an ALERT level message
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
    public function alert($message, $extra = array())
    {
        return $this->log(Logger::ALERT, $message, $extra);
    }

    /**
 	 * Logs a CRIT(ICAL) level message 
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
    public function crit($message, $extra = array())
    {
        return $this->log(Logger::CRIT, $message, $extra);
    }

    /**
 	 * Logs an ERROR level message 
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
    public function err($message, $extra = array())
    {
        return $this->log(Logger::ERR, $message, $extra);
    }

    /**
 	 * Logs a WARN(ing) level message 
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
    public function warn($message, $extra = array())
    {
        return $this->log(Logger::WARN, $message, $extra);
    }

    /**
 	 * Logs a NOTICE level message 
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
    public function notice($message, $extra = array())
    {
        return $this->log(Logger::NOTICE, $message, $extra);
    }

    /**
 	 * Logs an INFO level message  
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
    public function info($message, $extra = array())
    {
        return $this->log(Logger::INFO, $message, $extra);
    }

    /**
 	 * Logs a DEBUG level message 
	 * @param string $message
	 * @param array|Traversable $extra
	 * @return void
	 */
    public function debug($message, $extra = array())
    {
        return $this->log(Logger::DEBUG, $message, $extra);
    }




}