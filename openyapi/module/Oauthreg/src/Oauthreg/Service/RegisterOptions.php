<?php

namespace Oauthreg\Service;

use Zend\Stdlib\AbstractOptions;

class RegisterOptions extends AbstractOptions 
{
    /**
     * A default option value pair
     * @var string
     */
    protected $domainName   = 'localhost';
    protected $dnUuid       = "cd8f5f9f-e3e8-569f-87ef-f03c6cfc29bc";
    
    protected $smtpOptions  = array('name' => 'openy.es',
                                    'host' => 'smtp.gmail.com',
                                    'connection_class' => 'login',
                                    'port' => '465',
                                    'connection_config' => array(
                                        'ssl' => 'ssl',
                                        'username' => 'appopeny@gmail.com',
                                        'password' => '1234123',
                                    ));
   
    
    
    protected $verificationFrontEndpoint = "http:///verifyemail/";
    protected $newPsasswordFrontEndpoint = "http:///verifyrecoverpassword/";
    protected $allwaysAuthorizedRoutes   = array('oauthreg.rest.clientregister');
    protected $passwordCost              = 14;
    protected $isEnableXapikeyHeader     = false;
    
    protected $isEnableVerifyEmail       = true;
    protected $isEnableVerifyWithSms     = false;
    protected $isEnableVerifyWithSmsSendEmail = false;
    
    protected $isEnableAutoverifyUser    = false;
    protected $isEnableDeleteTemporalInfoAfterVerification = false;
    
    protected $maxNumberOfSms = 3; 
    
    protected $sentRecoverPasswordToEmail   = true;
    protected $sentRecoverPasswordLinkEmail = false;
    
    protected $forceSslModules = array('Oauthreg');
    
    
    
    /**
     * @param array (collection, entity, array header)
     * @example ('oauth' => array('oauth', '/%s', array('Accept' => 'application/vnd.module.v1+json'))
     */
    protected $smsUrl = 'https://rest.messagebird.com/';
    protected $smsResources = array ('messages' => array('endpoint'=>'messages',
                                     'collection'=>'/%s',
                                     'headers'=>array('Accept' => 'application/json',
                                                     'Content-Type'=>'application/json',
                                                     'Authorization'=>'AccessKey ARSVabwudlWy4Cw0lHocWN65B')
    ));
    
    

    
    
    
    
	/**
     * @return the $forceSslModules
     */
    public function getForceSslModules()
    {
        return $this->forceSslModules;
    }

	/**
     * @param multitype:string  $forceSslModules
     */
    public function setForceSslModules($forceSslModules)
    {
        $this->forceSslModules = $forceSslModules;
        return $this;
    }

	/**
     * @return the $sentRecoverPasswordLinkEmail
     */
    public function getSentRecoverPasswordLinkEmail()
    {
        return $this->sentRecoverPasswordLinkEmail;
    }

	/**
     * @param boolean $sentRecoverPasswordLinkEmail
     */
    public function setSentRecoverPasswordLinkEmail($sentRecoverPasswordLinkEmail)
    {
        $this->sentRecoverPasswordLinkEmail = $sentRecoverPasswordLinkEmail;
        return $this;
    }

	/**
     * @return the $sentRecoverPasswordToEmail
     */
    public function getSentRecoverPasswordToEmail()
    {
        return $this->sentRecoverPasswordToEmail;
    }

	/**
     * @param boolean $sentRecoverPasswordToEmail
     */
    public function setSentRecoverPasswordToEmail($sentRecoverPasswordToEmail)
    {
        $this->sentRecoverPasswordToEmail = $sentRecoverPasswordToEmail;
        return $this;
    }

	/**
     * @return the $maxNumberOfSms
     */
    public function getMaxNumberOfSms()
    {
        return $this->maxNumberOfSms;
    }

	/**
     * @param number $maxNumberOfSms
     */
    public function setMaxNumberOfSms($maxNumberOfSms)
    {
        $this->maxNumberOfSms = $maxNumberOfSms;
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
    public function getSmsResource($name = null)
    {
        if($name)
        {
            return $this->smsResources[$name];
        }
        return $this->getSmsResources();
        
    }
    
    

	/**
     * @return the $smsResources
     */
    public function getSmsResources()
    {
        return $this->smsResources;
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
     * @return the $isEnableAutoverifyUser
     */
    public function getIsEnableAutoverifyUser()
    {
        return $this->isEnableAutoverifyUser;
    }

	/**
     * @param boolean $isEnableAutoverifyUser
     */
    public function setIsEnableAutoverifyUser($isEnableAutoverifyUser)
    {
        $this->isEnableAutoverifyUser = $isEnableAutoverifyUser;
        return $this;
    }

	/**
     * @return the $isEnableVerifyWithSms
     */
    public function getIsEnableVerifyWithSms()
    {
        return $this->isEnableVerifyWithSms;
    }

	/**
     * @param boolean $isEnableVerifyWithSms
     */
    public function setIsEnableVerifyWithSms($isEnableVerifyWithSms)
    {
        $this->isEnableVerifyWithSms = $isEnableVerifyWithSms;
        return $this;
    }

	/**
     * @return the $isEnableDeleteTemporalInfoAfterVerification
     */
    public function getIsEnableDeleteTemporalInfoAfterVerification()
    {
        return $this->isEnableDeleteTemporalInfoAfterVerification;
    }

	/**
     * @param boolean $isEnableDeleteTemporalInfoAfterVerification
     */
    public function setIsEnableDeleteTemporalInfoAfterVerification($isEnableDeleteTemporalInfoAfterVerification)
    {
        $this->isEnableDeleteTemporalInfoAfterVerification = $isEnableDeleteTemporalInfoAfterVerification;
        return $this;
    }

	/**
     * @return the $isEnableVerifyEmail
     */
    public function getIsEnableVerifyEmail()
    {
        return $this->isEnableVerifyEmail;
    }

	/**
     * @param boolean $isEnableVerifyEmail
     */
    public function setIsEnableVerifyEmail($isEnableVerifyEmail)
    {
        $this->isEnableVerifyEmail = $isEnableVerifyEmail;
        return $this;
    }

	/**
     * @return the $isEnableXapikeyHeader
     */
    public function getIsEnableXapikeyHeader()
    {
        return $this->isEnableXapikeyHeader;
    }

	/**
     * @param boolean $isEnableXapikeyHeader
     */
    public function setIsEnableXapikeyHeader($isEnableXapikeyHeader)
    {
        $this->isEnableXapikeyHeader = $isEnableXapikeyHeader;
        return $this;
    }

	/**
     * @return the $allwaysAuthorizedRoutes
     */
    public function getAllwaysAuthorizedRoutes()
    {
        return $this->allwaysAuthorizedRoutes;
    }

	/**
     * @param multitype: $allwaysAuthorizedRoutes
     */
    public function setAllwaysAuthorizedRoutes($allwaysAuthorizedRoutes)
    {
        $this->allwaysAuthorizedRoutes = $allwaysAuthorizedRoutes;
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
     * @return the $verificationFrontEndpoint
     */
    public function getVerificationFrontEndpoint()
    {
        return $this->verificationFrontEndpoint;
    }

	/**
     * @param string $verificationFrontEndpoint
     */
    public function setVerificationFrontEndpoint($verificationFrontEndpoint)
    {
        $this->verificationFrontEndpoint = $verificationFrontEndpoint;
        return $this;
    }
    
	/**
     * @return the $newPsasswordFrontEndpoint
     */
    public function getNewPsasswordFrontEndpoint()
    {
        return $this->newPsasswordFrontEndpoint;
    }

	/**
     * @param string $newPsasswordFrontEndpoint
     */
    public function setNewPsasswordFrontEndpoint($newPsasswordFrontEndpoint)
    {
        $this->newPsasswordFrontEndpoint = $newPsasswordFrontEndpoint;
        return $this;
    }

    
	


	
	
}
