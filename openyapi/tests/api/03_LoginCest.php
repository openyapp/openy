<?php

class LoginCest
{
    protected $api;
    protected $loginData;
    protected $clientData;
    private $responseData = array();
    
    protected function _inject(\Helper\Api $api)
    {
        $this->api = $api;
    }
    
    /************************************* TEST *********************************************/

    public function checkClientRegister(ApiTester $I)
    {
        $I = $this->api->setHeadersWithoutAuthorization($I);
        $this->clientData = $this->api->getData('clientData');
        $I->comment('I read this fixtures: clientData');
        $I->comment('------------------ Start -------------------');
        $I->sendPOST($this->api->getUrl().'/clientregister', $this->clientData);
        $this->responseData['privatekey'] = $I->grabDataFromResponseByJsonPath('"privatekey"')[0];
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"privatekey"');
        $I->seeResponseContains('"publickey"');
        $I->seeResponseContains('"osversion":"Codeception ApiTest"');
        $I->seeInDatabase('app_register', ['privatekey' => $this->responseData['privatekey']]);
        $I->comment('------------------ End ---------------------');
    }
    
    public function checkLogin(ApiTester $I)
    {
        $I = $this->api->setHeadersWithoutAuthorization($I);
        $this->loginData = $this->api->getData('loginData');
        $this->loginData['client_id'] = $this->responseData['privatekey'];
        
        $I->comment('I read this fixtures: loginData');
        $I->comment('------------------ Start -------------------');
        $I->sendPOST($this->api->getUrl().'/oauth', $this->loginData);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"access_token"');
        $I->seeResponseContains('"refresh_token"');
    
        $this->responseData['access_token'] = $I->grabDataFromResponseByJsonPath('"access_token"')[0];
        $this->responseData['refresh_token'] = $I->grabDataFromResponseByJsonPath('"refresh_token"')[0];
    
        $I->seeInDatabase('oauth_access_tokens', ['access_token' => $this->responseData['access_token']]);
        $I->seeInDatabase('oauth_refresh_tokens', ['refresh_token' => $this->responseData['refresh_token']]);
    
        $I->comment('------------------ End ---------------------');    
    }
}
