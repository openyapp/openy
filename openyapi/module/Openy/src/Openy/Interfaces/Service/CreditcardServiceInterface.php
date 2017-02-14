<?php
/**
 * Interface.
 * Credit Cards Service Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\Methods
 * @category CreditCard
 * @see Openy\Module
 *
 */
namespace Openy\Interfaces\Service;

use Openy\Model\Creditcard\CreditcardEntity as CreditCard;
use Openy\Model\AbstractCollection;

/**
 * CreditcardServiceInterface.
 * Defines functions for a Service managing Credit Cards
 *
 * @uses Openy\Model\Creditcard\CreditcardEntity Credit Card Entity
 * @uses Openy\Model\AbstractCollection Collections abstract base class 
 * @uses Openy\Intefarces\MapperInterface Mapper Interface
 * @see Openy\Service\CreditcardService Credit Card Service
 *
 */
interface CreditcardServiceInterface
{

	/**
	 * Invalidates by cancelling them, registered user credit cards listed in $collection.
	 * if Collection is null, then all credit cards are invalidated.
	 * If no user is given, current session user should be taken
	 * @param  mixed\null     $user       User sho owns credit cards
	 * @param  AbstractCollection|null $collection Collection of credit cards to be invalidated
	 * @return bool                       True if success or false if no cards where invalidated
	 */
    public function cancelCreditcards($user=null, AbstractCollection $collection=null);


    /**
     * Gets token associated with given credit card
     * @param  CreditCard $creditcard Credit card bound to token
     * @return String                 The token associated to credit card
     */
    public function getToken(CreditCard $creditcard);


    /**
     * Sets token for with given credit card
     * @param  CreditCard  $creditcard Credit card to bind to token
     * @param  String      $token      The token associated to credit card
     * @return CreditcardServiceInterface
     */
    public function setToken(CreditCard $creditcard, $token);


    /**
     * Returns a Credit card repository
     * @return Openy\Intefarces\MapperInterface The credit card mapper
     */
    public function getRepository();

}

