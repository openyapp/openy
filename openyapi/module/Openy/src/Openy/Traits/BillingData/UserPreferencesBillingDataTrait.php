<?php

namespace Openy\Traits\BillingData;

//use Openy\Interfaces\BillingDataInterface;

trait UserPreferencesBillingDataTrait
{

    public function getFullName()
    {
        return $this->inv_name;
    }

    public function getAddress()
    {
        return explode(' ',[$this->inv_address, $this->inv_postal_code, $this->inv_locality, $this->$inv_country]);
    }

    public function getID()
    {
        return $this->inv_document.'('.$this->inv_document_type.')';
    }

    public function getWeb()
    {
        return NULL;
    }

    public function getLogo()
    {
        return NULL;
    }

    public function getMail()
    {
        return NULL;
    }

    public function getPhone()
    {
        return NULL;
    }

    public function isComplete()
    {
        $result = TRUE;
        foreach(['name','address','postal_code','locality','country','document','document_type'] as $what):
            $property = 'inv_'.$what;
            $value = $this->{$property};
            $result = $result && !empty($value);
        endforeach;
        return $result;
    }
}