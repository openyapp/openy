<?php
/**
 * Service Interface.
 * Contains interface for AbstractService and descendants
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 *
 */
namespace Openy\Interfaces;

use Openy\Interfaces\Properties\MapperInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Openy\Interfaces\Aware\PreferenceAwareInterface;
use Openy\Interfaces\Properties\UserPreferencesInterface;
use Openy\Interfaces\Properties\OptionsInterface;

/**
 * Service Interface.
 * Extends following interfaces,  
 *
 * * Properties\MapperInterface
 * * ServiceLocatorAwareInterface
 * * PreferenceAwareInterface
 * * Properties\UserPreferencesInterface
 * * Properties\OptionsInterface
 *
 * as long as has following properties
 * 
 * * A (main) Mapper
 * * A Zend Service Locator
 * * A Current Preferences Service 
 * * A set of User Preferences
 * * A set of Options
 * 
 * @uses Openy\Interfaces\Properties\MapperInterface Mapper Property Interface
 * @uses Zend\ServiceManager\ServiceLocatorAwareInterface Zend ServiceLocator Aware Interface
 * @uses Openy\Interfaces\Aware\PreferenceAwareInterface Preference Service Aware Interface
 * @uses Openy\Interfaces\Properties\UserPreferencesInterface User Preferences Property Interface
 * @uses Openy\Interfaces\Properties\OptionsInterface Options Property Interface 
 */
interface ServiceInterface
	extends	MapperInterface,
			ServiceLocatorAwareInterface,
			PreferenceAwareInterface,
			UserPreferencesInterface,
			OptionsInterface
{
}