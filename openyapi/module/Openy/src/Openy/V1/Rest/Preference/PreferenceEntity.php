<?php
namespace Openy\V1\Rest\Preference;

use Openy\Traits\BillingData\UserPreferencesBillingDataTrait;
use Openy\Interfaces\Classes\BillingDataInterface;

class PreferenceEntity
    implements BillingDataInterface
{

    use UserPreferencesBillingDataTrait;

    public $iduser;
    public $payment_pin;
    public $default_credit_card;
    public $inv_name;
    public $inv_country;
    public $inv_address;
    public $inv_locality;
    public $inv_postal_code;
    public $inv_document_type;
    public $inv_document;
    public $inv_cicle;
    public $locale;
}
