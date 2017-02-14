<?php

class UserRegisterCest
{
    protected $api;
    protected $userData;
    private $responseData = array();
    
    protected function _inject(\Helper\Api $api)
    {
        $this->api = $api;
    }
    
    /************************************* TEST *********************************************/
    
    public function checkUserRegister(ApiTester $I)
    {
        $I = $this->api->setHeadersWithoutAuthorization($I);
        $this->userData = $this->api->getData('userData');
        $I->comment('I read this fixtures: userData');
        $I->comment('------------------ Start -------------------');
        $I->sendPOST($this->api->getUrl().'/register', $this->userData);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"email"');
        $I->seeResponseContains('"iduser"');
        $I->seeResponseContains('"token"');
        $I->seeInDatabase('oauth_register', ['email' => $this->userData['email']]);
    
        $this->responseData['iduser'] = $I->grabDataFromResponseByJsonPath('"iduser"')[0];
        $this->responseData['code'] = $I->grabFromDatabase('oauth_register', 'code', array('email' => $this->userData['email']));
        $I->seeInLastEmail($this->responseData['code']);
    
        $I->seeInLastEmail('Código de validación con SMS enviado al 34'.$this->userData['phone_number']);
        $I->seeLastEmailWasSentFrom('appopeny@gmail.com');
        $I->seeLastEmailWasSentTo($this->userData['email']);
    
        $I->comment('------------------ End ---------------------');
    
    }
    
    public function checkValidateSMS(ApiTester $I)
    {
        $I = $this->api->setHeadersWithoutAuthorization($I);
        $I->comment('------------------ Start -------------------');
        $I->sendGET($this->api->getUrl().'/verifysms/'.$this->responseData['code'].'/'.$this->responseData['iduser']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"result":true');
        $I->comment('------------------ End ---------------------');
    
    }
}
