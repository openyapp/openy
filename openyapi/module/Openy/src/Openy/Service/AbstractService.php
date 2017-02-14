<?php

namespace Openy\Service;

// Mapper property
use Openy\Interfaces\MapperInterface;
use Openy\Traits\Properties\MapperTrait;

// User Prefs property
use Openy\V1\Rest\Preference\PreferenceEntity;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Openy\Traits\Aware\PreferenceAwareTrait;
use Openy\Traits\Properties\UserPreferencesTrait;

// Options property
use Zend\Stdlib\AbstractOptions;
use Openy\Traits\Properties\OptionsTrait;

// Any Service property
use Openy\Traits\Properties\ServicePropertiesGetterTrait;

// Service class Interface
use Openy\Interfaces\ServiceInterface;

abstract class AbstractService
	implements  ServiceInterface
{
	use MapperTrait,
		ServiceLocatorAwareTrait,
		PreferenceAwareTrait,
		UserPreferencesTrait,
		OptionsTrait,
		ServicePropertiesGetterTrait;
	

	/**
	 * User requesting the API, assumed as current session owner
	 * @var \Oauthreg\V1\Rest\Oauthuser\OauthuserEntity
	 */
	protected $currentUser;
	
	public function __construct(
			MapperInterface $mapper,
			$currentUser,
			PreferenceEntity $userPrefs = null,
			AbstractOptions $options
	)
	{
		$this->setMapper($mapper);
		$this->currentUser = $currentUser;
		$this->setUserPrefs($userPrefs);
		$this->setOptions($options);
	}
}