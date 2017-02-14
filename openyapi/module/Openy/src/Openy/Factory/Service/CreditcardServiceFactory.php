<?php
/**
 * Factory.
 * Credit Cards Service Factory
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\Methods
 * @category CreditCard
 * @see Openy\Module
 *
 */
namespace Openy\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Openy\Service\CreditcardService;

/**
 * CreditcardServiceFactory.
 * Factorizes Creditcard Service instance(s)
 *
 * @uses Openy\Service\CreditcardService Credit Cards Service class
 * @see \Openy\Interfaces\Service\CreditcardServiceInterface Credit Cards Service Interface
 * @uses Zend\ServiceManager\FactoryInterface Zend Factory Interface
 * @uses Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
 */
class CreditcardServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {

        $creditcardMapper   = $sl->get('Openy\V1\Rest\Creditcard\CreditcardMapper');
        $validatedCardsMapper=$sl->get('Openy\Mapper\ValidatedCard');
        $attemptsMapper     =$sl->get('Openy\Mapper\ValidationCardAttempts');
        $currentUser        = $sl->get('Oauthreg\Service\CurrentUser');
        $userPrefs          = $sl->get('CurrentUserPreferences');
        $options            = $sl->get('Openy\Service\OpenyOptions');
        $tpvoptions         = $sl->get('Openy\Service\TpvOptions');

        return new CreditcardService($creditcardMapper, $validatedCardsMapper, $attemptsMapper, $currentUser, $userPrefs, $options,$tpvoptions);
    }
}