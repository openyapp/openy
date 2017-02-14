<?php

namespace Openy\Options;

use Zend\Stdlib\AbstractOptions;
use Openy\Options\SubOptions;

defined('POLICY_BILLING_LOCAL_DATA') || define('POLICY_BILLING_LOCAL_DATA',1,TRUE);
defined('POLICY_BILLING_DB_DATA_IF_AVAILABLE') || define('POLICY_BILLING_DB_DATA_IF_AVAILABLE',2,TRUE);
defined('POLICY_BILLING_ALLWAYS_DB_DATA') || define('POLICY_BILLING_ALLWAYS_DB_DATA',3,TRUE);

class OpenyOptions extends AbstractOptions
{
    /**
     * A default option value pair
     * @var string
     */
    protected $domainName   = 'localhost';
    protected $dnUuid       = "cd8f5f9f-e3e8-569f-87ef-f03c6cfc29bc";

    protected $smtpOptions  = array('name' => 'Openy App',
                                    'host' => '.com',
                                    'connection_class' => 'login',
                                    'connection_config' => array(
                                        'username' => 'openy@.com',
                                        'password' => 'pass',
        ));
    protected $emailReceivers = array(
                                'contact@openy.es'    => '@gmail.com',
                                'feedback@openy.es'   => '@gmail.com',
                                'pos@openy.es'        => '@gmail.com',
                                'dev@openy.es'        => '@gmail.com'
    );

    /**
     * @var int
     */
    protected $passwordCost = 14;

    protected $jsonCollectionPerPageItems = 20;

    protected $installationDate;

    protected $isEnableAuthenticationHeader = true;

    protected $maxNumberOfPinsTries = 3;

    protected $daysToResetPinsTries = 14;

    protected $maxNumberOfAntifraudSms = 3;
    protected $isAntifraudVerificationRequired = true;

    protected $isEnableVerifyWithSmsSendEmail = false;
    protected $smsUrl = 'https://rest.messagebird.com/';
    protected $smsResources = array ('messages' => array('endpoint'=>'messages',
                                    'collection'=>'/%s',
                                    'headers'=>array('Accept' => 'application/json',
                                        'Content-Type'=>'application/json',
                                        'Authorization'=>'AccessKey ARSVabwudlWy4Cw0lHocWN65B')
    ));

    protected $creditcard = [
                             'limits' => [
                                'register' => [
                                    'similar_cards' => ['validated' => FALSE, 'non_validated' => 2],
                                    'single_cards' => FALSE
                                    ],
                                'validation' => 3,
                                ],
                             'policies' => [
                                'register' => [
                                    'favorite_first' => TRUE,
                                ],
                                'validation' => [
                                    'auto_activate' => TRUE,
                                    'favorite_last' => FALSE,
                                ]
                             ]
                            ];

    protected $payment = [
        'methods' => array(
            'default' => 'creditcard',
            'creditcard' => 1,
            'paypal' => 2,
            'voucher'=> 3,
            'credits'=> 4,
            ),
        'taxes' => array(
            1 => ['name'=>'IVA','locale'=>'es_ES','percent'=>'21']
            ),
    ];

    protected $billing = [
        'companies' => array(
            1 => array(
                'billingName'    => 'billingName',
                'billingAddress' => 'billingAddress',
                'billingId'      => 'billingId',
                'billingWeb'     => 'billingWeb',
                'billingLogo'    => 'openy',
                'billingMail'    => 'billingMail',
                'billingPhone'   => '',
            ),
        ),
        'policies' => array(
            POLICY_BILLING_LOCAL_DATA,
            POLICY_BILLING_DB_DATA_IF_AVAILABLE,
            POLICY_BILLING_ALLWAYS_DB_DATA
        ),
        'invoices' => array(
                'policy' => POLICY_BILLING_DB_DATA_IF_AVAILABLE,
                'company'=> 1,
        ),
        'receipts'=>array(
                'policy' => POLICY_BILLING_ALLWAYS_DB_DATA,
                'company'=> 1,
        ),
    ];

	protected $monitorsUrl = 'http:///opystation/';
	protected $refuelMonitors = array ('raisepump' => array('endpoint'=>'monitorraisepump',
                                'headers'=>array('Accept' => 'application/json',
                                'Content-Type'=>'application/json',
                                'Authorization'=>'Basic b3Blbnk6b3B5XzE=')
    ));



	/**
     * @return the $monitorsUrl
     */
    public function getMonitorsUrl()
    {
        return $this->monitorsUrl;
    }

	/**
     * @return the $refuelMonitors
     */
    public function getRefuelMonitors()
    {
        return $this->refuelMonitors;
    }

	/**
     * @param field_type $monitorsUrl
     */
    public function setMonitorsUrl($monitorsUrl)
    {
        $this->monitorsUrl = $monitorsUrl;
        return $this;
    }

	/**
     * @param field_type $refuelMonitors
     */
    public function setRefuelMonitors($refuelMonitors)
    {
        $this->refuelMonitors = $refuelMonitors;
        return $this;
    }

	/**
     * @return the $isAntifraudVerificationRequired
     */
    public function getIsAntifraudVerificationRequired()
    {
        return $this->isAntifraudVerificationRequired;
    }

	/**
     * @param boolean $isAntifraudVerificationRequired
     */
    public function setIsAntifraudVerificationRequired($isAntifraudVerificationRequired)
    {
        $this->isAntifraudVerificationRequired = $isAntifraudVerificationRequired;
        return $this;
    }

	/**
     * @return the $isEnableVerifyWithSmsSendEmail
     */
    public function getIsEnableVerifyWithSmsSendEmail()
    {
        return $this->isEnableVerifyWithSmsSendEmail;
    }

	/**
     * @param boolean $isEnableVerifyWithSmsSendEmail
     */
    public function setIsEnableVerifyWithSmsSendEmail($isEnableVerifyWithSmsSendEmail)
    {
        $this->isEnableVerifyWithSmsSendEmail = $isEnableVerifyWithSmsSendEmail;
        return $this;
    }

	/**
     * @return the $smsUrl
     */
    public function getSmsUrl()
    {
        return $this->smsUrl;
    }

	/**
     * @return the $smsResources
     */
    public function getSmsResources()
    {
        return $this->smsResources;
    }

    /**
     * @return the $smsResources
     */
    public function getSmsResource($name = null)
    {
        if($name)
        {
            return $this->smsResources[$name];
        }
        return $this->getSmsResources();

    }

	/**
     * @param string $smsUrl
     */
    public function setSmsUrl($smsUrl)
    {
        $this->smsUrl = $smsUrl;
        return $this;
    }

	/**
     * @param multitype:multitype:string multitype:string    $smsResources
     */
    public function setSmsResources($smsResources)
    {
        $this->smsResources = $smsResources;
        return $this;
    }

	/**
     * @return the $maxNumberOfAntifraudSms
     */
    public function getMaxNumberOfAntifraudSms()
    {
        return $this->maxNumberOfAntifraudSms;
    }

	/**
     * @param number $maxNumberOfAntifraudSms
     */
    public function setMaxNumberOfAntifraudSms($maxNumberOfAntifraudSms)
    {
        $this->maxNumberOfAntifraudSms = $maxNumberOfAntifraudSms;
        return $this;
    }

	/**
     * @return the $daysToResetPinsTries
     */
    public function getDaysToResetPinsTries()
    {
        return $this->daysToResetPinsTries;
    }

	/**
     * @param number $daysToResetPinsTries
     */
    public function setDaysToResetPinsTries($daysToResetPinsTries)
    {
        $this->daysToResetPinsTries = $daysToResetPinsTries;
        return $this;
    }

	/**
     * @return the $maxNumberOfPinsTries
     */
    public function getMaxNumberOfPinsTries()
    {
        return $this->maxNumberOfPinsTries;
    }

	/**
     * @param number $maxNumberOfPinsTries
     */
    public function setMaxNumberOfPinsTries($maxNumberOfPinsTries)
    {
        $this->maxNumberOfPinsTries = $maxNumberOfPinsTries;
        return $this;
    }

    /**
     * @return the $emailReceivers
     */
    public function getEmailReceivers()
    {
        return $this->emailReceivers;
    }

	/**
     * @param multitype:string  $emailReceivers
     */
    public function setEmailReceivers($emailReceivers)
    {
        $this->emailReceivers = $emailReceivers;
        return $this;
    }

	/**
     * @return the $isEnableAuthenticationHeader
     */
    public function getIsEnableAuthenticationHeader()
    {
        return $this->isEnableAuthenticationHeader;
    }

	/**
     * @param boolean $isEnableAuthenticationHeader
     */
    public function setIsEnableAuthenticationHeader($isEnableAuthenticationHeader)
    {
        $this->isEnableAuthenticationHeader = $isEnableAuthenticationHeader;
        return $this;
    }

	/**
     * @return the $installationDate
     */
    public function getInstallationDate()
    {
        return $this->installationDate;
    }

	/**
     * @param field_type $installationDate
     */
    public function setInstallationDate($installationDate)
    {
        $this->installationDate = $installationDate;
        return $this;
    }

	/**
     * @return the $jsonCollectionPerPageItems
     */
    public function getJsonCollectionPerPageItems()
    {
        return $this->jsonCollectionPerPageItems;
    }

	/**
     * @param number $jsonCollectionPerPageItems
     */
    public function setJsonCollectionPerPageItems($jsonCollectionPerPageItems)
    {
        $this->jsonCollectionPerPageItems = $jsonCollectionPerPageItems;
        return $this;
    }

	/**
     * @return the $smtpOptions
     */
    public function getSmtpOptions()
    {
        return $this->smtpOptions;
    }

    /**
     * @param multitype:string multitype:string   $smtpOptions
     */
    public function setSmtpOptions($smtpOptions)
    {
        $this->smtpOptions = $smtpOptions;
        return $this;
    }


	/**
	 * @return the $domainName
	 */
	public function getDomainName() {
		return $this->domainName;
	}

	/**
	 * @param string $domainName
	 */
	public function setDomainName($domainName) {
		$this->domainName = $domainName;
	}

	/**
	 * @return the $dnUuid
	 */
	public function getDnUuid() {
		return $this->dnUuid;
	}

	/**
	 * @param string $dnUuid
	 */
	public function setDnUuid($dnUuid) {
		$this->dnUuid = $dnUuid;
	}

	/**
	 * set password cost
	 *
	 * @param int $passwordCost
	 * @return ModuleOptions
	 */
	public function setPasswordCost($passwordCost)
	{
	    $this->passwordCost = $passwordCost;
	    return $this;
	}

	/**
	 * get password cost
	 *
	 * @return int
	 */
	public function getPasswordCost()
	{
	    return $this->passwordCost;
	}



    /**
     * Gets the value of paymentMethods.
     *
     * @param String $method the payment method to get
     * @return mixed
     */
    public function getPaymentMethods($method = null)
    {
        $result = $this->payment['methods'];
        if (!is_null($method)){
            if (array_key_exists($method, $result))
                $result = $result[$method];
            else
                $result = null;
        }
        elseif (is_null($method) && array_key_exists('default', $result))
            unset($result['default']);

        return $result;
    }

    /**
     * Sets the value of paymentMethods.
     *
     * @param mixed $paymentMethods the payment methods
     *
     * @return self
     */
    protected function setPaymentMethods($paymentMethods)
    {
        $this->payment['methods'] = (array)$paymentMethods;
        $this->payment['methods']['default'] = (array_key_exists('default', $paymentMethods) ? $paymentMethods['default'] : null);
        return $this;
    }


    /**
     * Gets the value of defaultPaymentMethod.
     *
     * @return mixed
     */
    public function getDefaultPaymentMethod()
    {
        $defaultPaymentMethodName = $this->getPaymentMethods('default');
        $defaultPaymentMethodCode = $this->getPaymentMethods($defaultPaymentMethodName);
        if (empty($defaultPaymentMethodName) || empty($defaultPaymentMethodCode))
            throw new \Zend\Config\Exception\RuntimeException('Default payment method not configured or does not exist', 500);
        else
            return $defaultPaymentMethodName;
    }

    /**
     * Sets the value of defaultPaymentMethod.
     *
     * @param mixed $defaultPaymentMethod the default payment method
     *
     * @return self
     */
    protected function setDefaultPaymentMethod($defaultPaymentMethod)
    {
        $defaultPaymentMethodCode = $this->getPaymentMethods($defaultPaymentMethod);

        if (empty($defaultPaymentMethod) || empty($defaultPaymentMethodCode))
            throw new \Zend\Config\Exception\RuntimeException('Configured default payment method does not exist', 500);
        else
            $this->payment['methods']['default'] = $defaultPaymentMethod;

        return $this;
    }


    protected function getCreditCard(){
        return new SubOptions($this->creditcard);
    }

    protected function setCreditCard($creditcard){
        $this->creditcard = $creditcard;
        return $this;
    }

    protected function getPayment(){
        return new SubOptions($this->payment);
    }

    protected function setPayment($payment){
        $this->payment = $payment;
        return $this;
    }

    protected function getBilling(){
        return new SubOptions($this->billing);
    }

    protected function setBilling($billing){
        $this->billing = $billing;
        return $this;
    }


}
