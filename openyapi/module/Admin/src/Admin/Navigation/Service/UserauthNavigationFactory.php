<?php
namespace Admin\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

class UserauthNavigationFactory extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'userauth';
    }
}