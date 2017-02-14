<?php
/**
 * POS (TPV) Options Interface.
 * Defines getter and setter for a property of a TpvOptions kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\POS
 * @category Configuration
 * @see Openy\Interfaces\Properties\Options
 * @see Openy\Service\AbstractService
 *
 */
namespace Openy\Interfaces\Properties;

use Openy\Options\TpvOptions;


/**
 * TpvOptionsInterface.
 * Defines getter and setter for a property of a TpvOptions kind
 * 
 * @uses Openy\Options\TpvOptions Openy POS (TPV) Options class
 * @see  Openy\Traits\Properties\TpvOptionsTrait Trait for managing TpvOptions property 
 */
interface TpvOptionsInterface
{
    public function setTpvOptions(TpvOptions $options);
    public function getTpvOptions();
}