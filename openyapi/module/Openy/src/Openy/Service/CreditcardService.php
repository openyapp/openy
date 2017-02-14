<?php
/**
 * Service.
 * Credit Cards Service
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\Methods
 * @category CreditCard
 * @see Openy\Module
 *
 */
namespace Openy\Service;

// CreditcardServiceInterface function arguments
use Openy\Model\Creditcard\CreditcardEntity;
use Openy\Model\AbstractCollection;

// Methods internal uses
use Openy\Interfaces\Classes\CreditCardDataInterface;
use Openy\Model\Transaction\TransactionEntity as Transaction;
use Openy\Model\Creditcard\ValidatedCreditcardEntity as ValidCard;
use \Zend\Json\Server\Error as ZendJsonError;


// Constructor Arguments
use Openy\Interfaces\MapperInterface;
use Openy\V1\Rest\Preference\PreferenceEntity;
use Zend\Stdlib\AbstractOptions;

// Extends and Implements
use Openy\Service\AbstractService as ParentService;
use Openy\Interfaces\Service\CreditcardServiceInterface;
use Openy\Interfaces\Aware\TransactionServiceAwareInterface;
use Openy\Traits\Aware\TransactionServiceAwareTrait;
use Openy\Interfaces\Aware\CoreAccessServiceAwareInterface;
use Openy\Traits\Aware\CoreAccessServiceAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Openy\Interfaces\Properties\TpvOptionsInterface;
use Openy\Traits\Properties\TpvOptionsTrait;
use Openy\Interfaces\Openy\Log\LogInterface;
use Openy\Traits\Openy\Log\LogTrait;

/**
 * Credit Cards Service.
 * Implements CreditcardServiceInterface
 * 
 * @uses Openy\Service\AbstractService AbstractService base class
 * @uses Openy\Interfaces\Service\CreditcardServiceInterface CreditsardServiceInterface
 * @uses Openy\Interfaces\Aware\TransactionServiceAwareInterface TransactionServiceAwareInterface
 * @uses Openy\Traits\Aware\TransactionServiceAwareTrait TransactionServiceAwareTrait
 * @uses Openy\Interfaces\Aware\CoreAccessServiceAwareInterface CoreAccessServiceAwareInterface
 * @uses Openy\Traits\Aware\CoreAccessServiceAwareTrait CoreAccessServiceAwareTrait
 * @uses Zend\ServiceManager\ServiceLocatorAwareInterface ServiceLocatorAwareInterface
 * @uses Zend\ServiceManager\ServiceLocatorAwareTrait ServiceLocatorAwareTrait
 * @uses Openy\Interfaces\Properties\TpvOptionsInterface TpvOptionsInterface
 * @uses Openy\Traits\Properties\TpvOptionsTrait TpvOptionsTrait
 * @uses Openy\Interfaces\Openy\Log\LogInterface LogInterface
 * @uses Openy\Traits\Openy\Log\LogTrait LogTrait
 *
 */
class CreditcardService
	extends ParentService
    implements CreditcardServiceInterface,
               TransactionServiceAwareInterface,
               CoreAccessServiceAwareInterface,
               ServiceLocatorAwareInterface,
               TpvOptionsInterface,
               LogInterface
{

    use TransactionServiceAwareTrait,
        CoreAccessServiceAwareTrait,
        ServiceLocatorAwareTrait,
        TpvOptionsTrait,
        LogTrait;
    
    protected $creditcardMapper;
    protected $validatedCardsMapper;
    protected $attemptsMapper;
	
    /**
     * Constructor.
     * @param MapperInterface $creditcardMapper
     * @param MapperInterface $validatedCardsMapper
     * @param MapperInterface $attemptsMapper
     * @param unknown $currentUser
     * @param PreferenceEntity $userPrefs
     * @param AbstractOptions $options
     * @param AbstractOptions $tpvoptions
     */
    public function __construct(MapperInterface $creditcardMapper,
                                MapperInterface $validatedCardsMapper,
                                MapperInterface $attemptsMapper,
                                $currentUser,
                                PreferenceEntity $userPrefs = null,
                                AbstractOptions $options = null,
                                AbstractOptions $tpvoptions)
    {        
        parent::__construct($creditcardMapper,$currentUser,$userPrefs,$options);
        $this->setTpvOptions($tpvoptions);
        $this->creditcardMapper   = &$this->mapper;
        $this->validatedCardsMapper = $validatedCardsMapper;
        $this->attemptsMapper = $attemptsMapper;        
    }

    /**
     * {@inheritDoc}
     * @see \Openy\Interfaces\Service\CredicardServiceInterface CrediccardServiceInterface
     */
    public function cancelCreditcards($user=null, AbstractCollection $collection=null){
        // TODO : Improve this with more cases (user is not current)
        // Collection is not null
        return $this->deleteAll();
    }
    
	/**
	 * @see \Openy\V1\Rest\Creditcard\CreditcardMapper::deleteAll() API CreditcardMapper deleteAll Method
	 */
    public function deleteAll()
    {
        $this->creditcardMapper->deleteAll();
    }
    
    public function delete($id){
        // TODO: THIS MAY CANCEL any TPV Opened Transaction (if not validated card)
        return $this->validatedCardsMapper->delete($id);
    }

    /**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Aware\CreditcardServiceAwareInterface
	 */
    public function getToken(CreditcardEntity $creditcard)
    {
        $card = $this->validatedCardsMapper->fetch($creditcard->idcreditcard);
        return $card->getToken();
    }

    /**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Aware\CreditcardServiceAwareInterface
	 */
    public function setToken(CreditcardEntity $creditcard, $token){
        $card = new ValidCard($creditcard);
        $card->token = $token;
        return $this->validatedCardsMapper->update($card->idcreditcard,$card);
    }


    /**
     * {@inheritDoc}
     * @return Openy\Interfaces\MapperInterface The Credit card mapper
     */
    public function getRepository(){
        return $this->creditcardMapper;
    }


    public function registerCreditCard(CreditCardDataInterface $card){
        $this->info('INFO: STARTING CREDIT CARD REGISTER');

        // 1. Register credit card in opy_credit_card
        $card = $this->creditcardMapper->insert($card);
        // 2. Launch a transaction for further validation
        $transaction = new Transaction(
                                    //TODO: IMPROVE TRANSACTION ID NUMBER GENERATION
/*                            $transactionid  = $this->getTpvOptions()->getTransaction()->getPrefix("creditcard")
                                            . substr($card->pan,-2) . $card->cvv,

//TODO: REMOVE THIS*/
                            $transactionid  = '2015'.substr($card->idcreditcard,0,8),

                            $merchantcode   = $this->getTpvOptions()->getDefaults("merchant_code"),
                            $terminal       = $this->getTpvOptions()->getDefaults("terminal"),
                            $secret         = $this->getTpvOptions()->getDefaults("secret"),
                            $amount         = rand(1,150)/100,
                            $creditcard     = $card,
                            $transactiontype=Transaction::TRANSACTION_TYPE_PREAUTH
                            );
        // 3. If transaction has been authorized, then proceed annotating transaction
        if ($this->getTransactionService()->getAuthorization($transaction)):
            $this->info('INFO: SUCCESS CHARGING CREDIT CARD REGISTER AMOUNT');
            $validcard = new ValidCard;
            $hydrator = new \Zend\Stdlib\Hydrator\ObjectProperty();
            $data = $hydrator->extract($card);
            $validcard = $hydrator->hydrate($data,$validcard);
            // TODO: Improve transctionid assignment via service delegated calls
            $validcard->transactionid = $transaction->transactionid;
            $validcard->token = $transaction->token;
            $validcard->validator = $this->currentUser->getUser('iduser');
            $validcard->validated = false; //CARD IS JUST AUTHORIZED, NOT VALIDATED

            $validcard = $this->validatedCardsMapper->insert($validcard);


            // Persist transaction in opy_credit_card table
            $card->transactionid = $transaction->transactionid;
            $card->updated = $card->created;
            $card = $this->creditcardMapper->update($validcard->idcreditcard,$card);

            return $card;
        else:
            $this->debug('ERROR: FAIL CHARGING CREDIT CARD REGISTER AMOUNT');
            $status = $this->getTransactionService()->getTransactionStatus($transaction);
            $error = new ZendJsonError;
            $error->setCode(500);
            $lang = $this->getUserPrefs()->locale;
            $error->setMessage($status->text($lang ? :"default"));
            $error->setData($status);
            $this->info('INFO: FINISHED CREDIT CARD REGISTER WITH ERRORS');
            $this->debug('ERRORS: Error at the end of Credit Card Register',["details" => (string)$error]);
            return $error;
        endif;

        $this->info('INFO: FINISHED CREDIT CARD REGISTER SUCCESSFULLY',["new card" => $validcard->idcreditcard]);
        return $card;
    }

    public function validateCreditCard(CreditCardDataInterface $card, $amount){
        $this->info('INFO: STARTING CREDIT CARD VALIDATION');

        $result = $this->checkValidationAttempts($card);
        if ($result instanceof ZendJsonError):
            $this->info('INFO: FINISHED CREDIT CARD VALIDATION WITH ERRORS');
            $this->debug('ERRORS: Error at the end of Credit Card Validation ',["details" => (string)$result]);
            return $result;
        else:
            $result = $this->checkCardExistance($card);
            if ($result instanceof ZendJsonError):
                $this->info('INFO: FINISHED CREDIT CARD VALIDATION WITH ERRORS');
                $this->debug('ERRORS: Error at the end of Credit Card Validation ',["details" => (string)$result]);
                return $result;
            else:
                // Right here, result must be a valid credit card with a transactionid
                $result = $this->checkTransactionAmount($result,$amount);
                if ($result instanceof ZendJsonError):
                    $this->info('INFO: FINISHED CREDIT CARD VALIDATION WITH ERRORS');
                    $this->debug('ERRORS: Error at the end of Credit Card Validation ',["details" => (string)$result]);
                else:
                    $this->info('INFO: FINISHED CREDIT CARD VALIDATION SUCCESSFULLY');
                endif;

            // TOO MUCH INFO IN THIS RESULT
                // Result must be a credit card with a transaction
                return $result;
            endif;
        endif;

        return $result;
    }

        protected function checkValidationAttempts(CreditCardDataInterface $card){
            $input = clone $card;
            $card = $this->creditcardMapper->fetch($card->idcreditcard);
            // Attempts Mapper fetch action is filtered by session user
            // and criteria is stablished against $card PAN and Expires values
            $card_attempts = $this->attemptsMapper->fetchByData($card);

            if ((int)$card_attempts->attempts >= $this->getOptions()->creditCard->getLimits('validation')){
                $error = new ZendJsonError;
                $error->setCode(403);
                $error->setMessage('Attempts limit reached ('.$this->getOptions()->creditCard->getLimits('validation').' attempts)');
                $error->setData($input);
                return $error;
            }
            return $card;
        }

        protected function checkCardExistance(CreditCardDataInterface $card){
            $input = clone $card;
            // Card Mapper is filtered by session user
            $found = $this->validatedCardsMapper->exists($card,$fetch_card = true);
            if (!$found){
                $error = new ZendJsonError;
                $error->setCode(404);
                $error->setMessage('Card not found');
                $error->setData($input);
                return $error;
            } // $Card has been retrieved from Repository due $fetch_card flag set to true
            elseif ($card->validated){
                $error = new ZendJsonError;
                $error->setCode(410);
                $error->setMessage('Card already validated');
                $error->setData($input);
                return $error;
            }
            return $card;
        }

        protected function checkTransactionAmount(CreditCardDataInterface $card, $amount){

            //Locate card registry matching transaction
            $transaction = new Transaction($card->transactionid);
            $transaction->transactionType = Transaction::TRANSACTION_TYPE_PREAUTH;
            $transaction = $this->getTransactionService()->fetchTransaction($transaction);

            if (!is_null($transaction->amount) && floatval($transaction->amount) === floatval($amount)){
                $card->validated = true;
                $card = $this->validatedCardsMapper->update($card->idcreditcard,$card);
                $card_attempts = $this->validate_attempts($card);
                return $card;
            }
            else{
                $attempts_remaining = $this->getOptions()->creditCard->getLimits('validation')
                                    - $this->increase_attempts($card);
                $error = new ZendJsonError;
                $error->setCode(401);
                $error->setMessage('Wrong amount');
                $error->setData((object)['attempts remaining'=>$attempts_remaining]);
                return $error;
            }
        }

        protected function validate_attempts(CreditcardEntity $card){
            $card_attempts = $this->attemptsMapper->fetchByData($card);
            if ((int)$card_attempts->attempts >= 1){
                $data = ["validated" => $card->validated];
                $card_attempts = $this->attemptsMapper->update($card,(object)$data);
            }
            return $card_attempts;
        }

        protected function increase_attempts(CreditcardEntity $card){
            $card_attempts = $this->attemptsMapper->fetchByData($card);
            $card_attempts->attempts = (int)$card_attempts->attempts + 1;
            $access = $this->getCoreAccessService()->getCurrentAccess();
            $card_attempts->accesses = [$access->idremoteaccess];
            $card_attempts->idcreditcard = [$card->idcreditcard];

            // First attempt
            if ((int)$card_attempts->attempts == 1){
                $card_attempts = $this->attemptsMapper->insert($card_attempts);
            }
            else{
                $card_attempts = $this->attemptsMapper->update($card,$card_attempts);
            }
            return $card_attempts->attempts;
        }

}