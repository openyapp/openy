<?php
namespace Openy\V1\Rpc\SendFeedback;

class SendFeedbackControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        $options = $services->get('Openy\Service\OpenyOptions');
        return new SendFeedbackController($options);
    }
}
