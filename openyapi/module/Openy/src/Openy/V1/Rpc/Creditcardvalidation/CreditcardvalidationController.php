<?php
namespace Openy\V1\Rpc\Creditcardvalidation;

use ZF\ContentNegotiation\JsonModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use Zend\Mvc\Controller\AbstractActionController;
use Openy\Interfaces\Service\CreditcardServiceInterface;
use Openy\Model\Classes\CreditCardDataEntity;

class CreditcardvalidationController extends AbstractActionController
{

	protected $creditcardService;


	public function __construct(CreditcardServiceInterface $creditcardService){
		$this->creditcardService = $creditcardService;
	}

	/**
	 * Gets post body data and makes a request
	 * @return JsonModel The success or fail status with a descriptive message
	 */
    public function creditcardvalidationAction()
    {
    	$post = $this->bodyParams();
		$card = new CreditCardDataEntity($post);

		$result = $this->creditcardService->validateCreditCard($card, $post['amount']);

		switch(get_class($result)):
			case 'Zend\Json\Server\Error':
				return new ApiProblemResponse(
		                new ApiProblem(
		                    $result->getCode() ,
		                    $result->getMessage(),
		                    'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
		                    null,
		                    ['additional details'=>$result->getData()]
		                )
		            );
				break;

			default:
				$hydrator = new \Zend\Stdlib\Hydrator\Reflection;
				$class = new \Openy\V1\Rest\Creditcard\CreditcardEntity;
				$result = $hydrator->extract($result);
				$result = $hydrator->hydrate($result,$class);
				$result = get_object_vars($result);
				return new JsonModel($result);
				break;
		endswitch;
    }
}
