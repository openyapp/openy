<?php
namespace Openy\Traits;


use Openy\Traits\PreferenceAwareTrait;
use Openy\Traits\OptionsTrait;
use Openy\Traits\Service\MapperTrait;
use Openy\Interfaces\MapperInterface;
use Zend\Stdlib\AbstractOptions;
use Openy\V1\Rest\Preference\PreferenceEntity;

trait ServiceTrait
{
    use OptionsTrait;
    use MapperTrait;
    use PreferenceAwareTrait{
        getUserPrefs as parentGetUserPrefs;
    }

	protected $mapper;
    protected $currentUser;
    protected $userPrefs;

    public function __construct(
        MapperInterface $mapper,
        $currentUser,
        PreferenceEntity $userPrefs = null,
        AbstractOptions $options = null
        )
    {
        $this->setMapper($mapper);
        $this->setOptions($options);
        $this->currentUser = $currentUser;
        $this->userPrefs = $userPrefs;
    }

    public function getUserPrefs($property = null)
    {
        $this->userPrefs = $this->userPrefs ? : $this->parentGetUserPrefs();
        if ($property)
            return $this->userPrefs->{$property};

        return $this->userPrefs;
    }


}