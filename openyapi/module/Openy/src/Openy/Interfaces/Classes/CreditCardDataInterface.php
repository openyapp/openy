<?php

namespace Openy\Interfaces\Classes;

interface CreditCardDataInterface
{
	public function getIdCreditCard();
	public function getCardUsername();
	public function getPan();
	public function getCVV();
	public function getYear();
	public function getMonth();
	public function getToken();
}