<?php
/**
 * BillingData Trait.
 * Implements BillingDataInterface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Classes\Billing
 *
 */
namespace Openy\Traits\Classes;

use Openy\Interfaces\Classes\BillingDataInterface;

/**
 * BillingData Trait.
 * Achive the implementation of BillingDataInterface
 *
 * @uses Openy\Interfaces\BillingDataInterface Billing Data Interface
 *
 */
trait BillingDataTrait
{
    /**
     * (Full) Name (person or company)
     * @var String
     */
	public $billingName;

    /**
     * (Full) Address
     * @var String
     */
	public $billingAddress;

    /**
     * National Id
     * @var String
     */
	public $billingId;

    /**
     * Website
     * @var String
     */
	public $billingWeb;

    /**
     * Logo icon name
     * @var String
     */
	public $billingLogo;

    /**
     * Email address
     * @var String
     */
	public $billingMail;

    /**
     * Phone number
     * @var String
     */
	public $billingPhone;


    /**
     * Constructor
     *
     * Creates a new instance trying to initialize own properties with given object ones
     *
     * @param object|NULL $object Object to copy
     * @ignore
     */
	public function __construct($object=null){
		if (is_object($object)){
			$uses = class_uses($object);
			if (array_key_exists('BillingDataTrait',$uses) || $object instanceof BillingDataInterface){
				$this->billingName 		= $object->getFullName();
				$this->billingAddress 	= $object->getAddress();
				$this->billingId 		= $object->getID();
				$this->billingWeb 		= $object->getWeb();
				$this->billingLogo 		= $object->getLogo();
				$this->billingMail 		= $object->getMail();
				$this->billingPhone  	= $object->getPhone();
			}
		}
        elseif (is_array($object)){
            foreach(get_object_vars($this) as $property=>$value)
                $this->{$property} = (array_key_exists($property, $object) ? $object[$property] : $value);
        }
	}

    /**
     * Gets the value of billingName.
     *
     * @return String
     */
    public function getFullName()
    {
        return $this->billingName;
    }

    /**
     * Gets the value of billingAddress.
     *
     * @return String
     */
    public function getAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Gets the value of billingId.
     *
     * @return String
     */
    public function getID()
    {
        return $this->billingId;
    }

    /**
     * Gets the value of billingWeb.
     *
     * @return String
     */
    public function getWeb()
    {
        return $this->billingWeb;
    }

    /**
     * Gets the value of billingLogo.
     *
     * @return String
     */
    public function getLogo()
    {
        return $this->billingLogo;
    }

    /**
     * Gets the value of billingMail.
     *
     * @return String
     */
    public function getMail()
    {
        return $this->billingMail;
    }

    /**
     * Gets the value of billingPhone.
     *
     * @return String
     */
    public function getPhone()
    {
        return $this->billingPhone;
    }

    /**
     * Is billing data complete?
     *
     * Checks if Id, Address and FullName have values
     *
     * @return boolean TRUE if all of them are valued
     */
    public function isComplete()
    {
        $result = TRUE;
        foreach(['Id','Address','FullName'] as $what):
            $getter = 'get'.$what;
            $value = $this->{$getter}();
            $result = $result && !empty($value);
        endforeach;
        return $result;
    }
}