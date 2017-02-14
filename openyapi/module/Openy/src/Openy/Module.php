<?php
namespace Openy;

use ZF\Apigility\Provider\ApigilityProviderInterface;
use Zend\Mvc\MvcEvent;
use ZF\MvcAuth\MvcAuthEvent;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Openy Module.
 *
 * * Defines Service Locator Service names
 * * Sets config paths
 * * Binds namespace to Module folder
 * * Prepends authorization listener at Bootstrap time
 * * Asks for SSL over HTTP when configured
 *
 * @see  Openy\Module::getServiceConfig Service aliases (used by Service Locator)
 *
 */
class Module implements ApigilityProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'ZF\Apigility\Autoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'initializers' => [
                'ZfcRbac\Initializer\AuthorizationServiceInitializer'
            ],
            'invokables' => array(
               'Openy\Service\TestTransaction' => 'Openy\Service\TestTransaction',
               'Openy\Service\TPV\SOAP\Test' => 'Openy\Service\TestSoap',
               'Openy\Service\OrderStatus' => 'Openy\Service\OrderStatusService',
               'Openy\Service\Core\Access' => 'Openy\Service\Core\AccessService'
            ),

            'factories' => array(
                //CORE : ACCESS
                'Openy\Mapper\Core\Access' => 'Openy\Factory\Mapper\AccessMapperFactory',

                // PREFERENCES
                'CurrentUserPreferences' => function ($sm) {
                    $currentPreferencesService = $sm->get('Openy\Service\CurrentPreferences');
                    return $currentPreferencesService->getPreference();
                },
                'Openy\Service\CurrentPreferences' => function ($sm) {
                    $currentUser    = $sm->get('Oauthreg\Service\CurrentUser');
                    $repository     = $sm->get('Openy\V1\Rest\Preference\PreferenceMapper');
                    return new \Openy\Service\CurrentPreferences($currentUser, $repository);
                },

                'Openy\Service\SecurityChain' => function ($sm) {
                    $currentUser    = $sm->get('Oauthreg\Service\CurrentUser');
                    return new \Openy\Service\SecurityChain($currentUser);
                },

                'Openy\Service\OpenyOptions' => function ($sm) {
                    $config = $sm->get('Config');
                    return new \Openy\Options\OpenyOptions(isset($config['openy']) ? $config['openy'] : array());
                },
                'Openy\Service\TpvOptions' => function ($sm) {
                    $config = $sm->get('Config');
                    return new \Openy\Options\TpvOptions(array_key_exists('tpv',$config) ? $config['tpv'] : array());
                },
                'Openy\Service\PaymentOptions' => function ($sm) {
                    $config = $sm->get('Config');
                    $config = array_key_exists('openy',$config) ? $config['openy'] : array();
                    $config = array_key_exists('payment',$config) ? $config['payment'] : array();
                    return new \Openy\Options\PaymentOptions($config);
                },
                'Openy\Service\BillingOptions' => function ($sm) {
                    $config = $sm->get('Config');
                    $config = array_key_exists('openy',$config) ? $config['openy'] : array();
                    $config = array_key_exists('billing',$config) ? $config['billing'] : array();
                    return new \Openy\Options\BillingOptions($config);
                },
                // COMPANIES
                'Openy\V1\Rest\Company\CompanyMapper' => 'Openy\Factory\Mapper\CompanyMapperFactory',
                'Openy\Mapper\TpvCompany' => 'Openy\Factory\Mapper\TpvCompanyMapperFactory',
                'Openy\Service\Company' => 'Openy\Factory\Service\CompanyServiceFactory',
                // PREFERENCES
                'Openy\V1\Rest\Preference\PreferenceMapper' => function ($sm) {
                    $adapterMaster  = $sm->get('dbMasterAdapter');
                    $adapterSlave   = $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Openy\Service\OpenyOptions');
                    $currentUser    = $sm->get('Oauthreg\Service\CurrentUser');
                    return new \Openy\V1\Rest\Preference\PreferenceMapper($adapterMaster, $adapterSlave, $options, $currentUser);
                },

                'Openy\V1\Rest\Liststations\ListstationsMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Openy\Service\OpenyOptions');
                    $currentUser    = $sm->get('Oauthreg\Service\CurrentUser');
                    return new \Openy\V1\Rest\Liststations\ListstationsMapper($adapterMaster, $adapterSlave, $options, $currentUser);
                },
                //OFF_STATIONS
                'Openy\V1\Rest\Station\StationMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Openy\Service\OpenyOptions');
                    $currentUser    = $sm->get('Oauthreg\Service\CurrentUser');
                    return new \Openy\V1\Rest\Station\StationMapper($adapterMaster, $adapterSlave, $options, $currentUser);
                },
                //OPY_STATIONS
                'Openy\Mapper\OpyStation'=> 'Openy\Factory\Mapper\OpyStationMapperFactory',
                'Openy\Service\OpyStation' => 'Openy\Factory\Service\OpyStationServiceFactory',

                'Openy\V1\Rest\Price\PriceMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Openy\Service\OpenyOptions');
                    return new \Openy\V1\Rest\Price\PriceMapper($adapterMaster, $adapterSlave, $options);
                },

                'Openy\V1\Rest\Fueltype\FueltypeMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Openy\Service\OpenyOptions');
                    return new \Openy\V1\Rest\Fueltype\FueltypeMapper($adapterMaster, $adapterSlave, $options);
                },

                'Openy\V1\Rest\Favoritestation\FavoritestationMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Openy\Service\OpenyOptions');
                    $currentUser    = $sm->get('Oauthreg\Service\CurrentUser');
                    return new \Openy\V1\Rest\Favoritestation\FavoritestationMapper($adapterMaster, $adapterSlave, $options, $currentUser);
                },

                'Openy\V1\Rest\Refuel\RefuelMapper' =>  function ($sm) {
                    $adapterMaster 	    = $sm->get('dbMasterAdapter');
                    $adapterSlave 	    = $sm->get('dbSlaveAdapter');
                    $options            = $sm->get('Openy\Service\OpenyOptions');
                    $orderService       = $sm->get('Openy\Service\Order');
                    $currentUser        = $sm->get('Oauthreg\Service\CurrentUser');
                    $securityChain      = $sm->get('Openy\Service\SecurityChain');
                    $refuelService      = $sm->get('Opypos\Service\RefuelService');
                    $priceMapper        = $sm->get('Opypos\V1\Rest\Price\PriceMapper');
                    $request            = $sm->get('Request');
                    return new \Openy\V1\Rest\Refuel\RefuelMapper($adapterMaster, $adapterSlave, $options, $orderService, $currentUser, $securityChain, $refuelService, $priceMapper, $request);
                },

                'Openy\V1\Rest\Collect\CollectMapper' =>  function ($sm) {
                    $adapterMaster 	    = $sm->get('dbMasterAdapter');
                    $adapterSlave 	    = $sm->get('dbSlaveAdapter');
                    $options            = $sm->get('Openy\Service\OpenyOptions');
                    $orderService       = $sm->get('Openy\Service\Order');
                    $currentUser        = $sm->get('Oauthreg\Service\CurrentUser');
                    $securityChain      = $sm->get('Openy\Service\SecurityChain');
                    $refuelService      = $sm->get('Opypos\Service\RefuelService');
                    return new \Openy\V1\Rest\Collect\CollectMapper($adapterMaster, $adapterSlave, $options, $orderService, $currentUser, $securityChain, $refuelService);
                },

                // CREDITCARD
                'Openy\Mapper\ValidatedCard' => 'Openy\Factory\Mapper\ValidatedCardsMapperFactory',
                'Openy\Mapper\ValidationCardAttempts' => 'Openy\Factory\Mapper\ValidationCardAttemptsMapperFactory',
                'Openy\V1\Rest\Creditcard\CreditcardMapper' => 'Openy\Factory\Mapper\CreditCardMapperFactory',
                'Openy\Service\CreditCard' => 'Openy\Factory\Service\CreditcardServiceFactory',

                // PAYMENTS
                'Openy\Mapper\Payment' => 'Openy\Factory\Mapper\PaymentMapperFactory',
                'Openy\Service\Payment' => 'Openy\Factory\Service\PaymentServiceFactory',
                // PAYMENT RECEIPTS
                'Openy\Service\Receipt' => 'Openy\Factory\Service\ReceiptServiceFactory',
                'Openy\Mapper\Receipt' => 'Openy\Factory\Mapper\ReceiptMapperFactory',
                'Openy\V1\Rest\Receipt\ReceiptMapper' => 'Openy\V1\Rest\Receipt\ReceiptMapperFactory',

                // INVOICES
                'Openy\Service\Invoice' => 'Openy\Factory\Service\InvoiceServiceFactory',
                'Openy\Mapper\Invoice' => 'Openy\Factory\Mapper\InvoiceMapperFactory',
                'Openy\V1\Rest\Invoice\InvoiceMapper' => 'Openy\V1\Rest\Invoice\InvoiceMapperFactory',
                'Openy\V1\Rest\Invoice\InvoiceHydrator' => 'Openy\V1\Rest\Invoice\InvoiceHydratorFactory',

                // ORDERS
                'Openy\OrderMapper' => 'Openy\Factory\Mapper\OrderMapperFactory',
                'Openy\Service\Order' => 'Openy\Factory\Service\OrderServiceFactory',
                //'Openy\Service\OrderStatus' => See 'Openy\Service\OrderStatus' at invokables

                // TRANSACTIONS
                'Openy\Model\Transaction\TransactionMapper' => 'Openy\Factory\Mapper\TransactionMapperFactory',
                'Openy\Service\Transaction' => 'Openy\Factory\Service\TransactionServiceFactory',

                // SOAP
                'Openy\Service\TPV\SOAP' => 'Openy\Factory\Service\SoapServiceFactory',
                'Openy\Mapper\TPV\SOAP' =>  'Openy\Factory\Mapper\SoapMapperFactory',
                'Openy\Mapper\TPV\Request'  => 'Openy\Factory\Mapper\SOAP\RequestMapperFactory',
                'Openy\Mapper\TPV\Response'  => 'Openy\Factory\Mapper\SOAP\ResponseMapperFactory',

                // Authentication
                'Openy\Authentication\Adapter\HeaderAuthentication'             => 'Openy\Authentication\Factory\HeaderAuthenticationFactory',
                'Openy\Authentication\Listener\ApiAuthenticationListener'       => 'Openy\Authentication\Factory\AuthenticationListenerFactory',
            ),
        );
    }



    public function onBootstrap(MvcEvent $event)
    {

        $app = $event->getApplication();
        $sm  = $app->getServiceManager();
        $em  = $app->getEventManager();
        $options        = $sm->get('Openy\Service\OpenyOptions');


        $listener = $sm->get('Openy\Authentication\Listener\ApiAuthenticationListener');

        //$em->getSharedManager()->attach('Openy\V1\Rest\Fueltype\Controller', 'dispatch', $listener);

        //MvcAuthEvent::EVENT_AUTHENTICATION

        // Attach after routing has been done
        /*
        $em ->attach(MvcEvent:: EVENT_ROUTE, array (
            $this ,
            'onRoute'
        ), - 100);
        */


        if($options->getIsEnableAuthenticationHeader())
        {
            $em->attach(
                MvcAuthEvent::EVENT_AUTHORIZATION,
                $listener,
                100
            );
        }



//         $em->getSharedManager()->attach('Openy\V1\Rest\Fueltype\FueltypeResource', MvcAuthEvent::EVENT_AUTHORIZATION, $listener);
    }




    public function onRoute(MvcEvent $e)
    {
        $services = $e ->getApplication()->getServiceManager();
        $serverUrlHelper = $services ->get('ViewHelperManager' )->get( 'ServerUrl');
        // Enforce https communication
        if ($serverUrlHelper ->getScheme() == 'http' )
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    403 ,
                    'URI requires HTTPS' ,
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-496' ,
                    'No Cert'
                )
            );
        }
    }

}
