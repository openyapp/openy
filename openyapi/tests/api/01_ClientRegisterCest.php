<?php

class ClientRegiterCest
{
    protected $api;
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
    
    public function checkGetClientsRegisteredWithAdmin(ApiTester $I)
    {
        $I = $this->api->setHeadersWithAdminAuthorization($I);
        $I->comment('------------------ Start -------------------');
        $I->sendGET($this->api->getUrl().'/clientregister');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"_embedded"');
        $I->seeResponseContains('"clientregister"');
        $I->comment('------------------ End ---------------------');    
    }
    
    public function checkGetClientsRegisteredWithNotAdmin(ApiTester $I)
    {
        $I = $this->api->setHeadersWithAuthorization($I);
        $I->comment('------------------ Start -------------------');
        $I->sendGET($this->api->getUrl().'/clientregister');
        $I->seeResponseCodeIs(403);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"Forbidden"');
        $I->seeResponseContains('"detail"');
        $I->comment('------------------ End ---------------------');    
    }
    
    public function checkDeleteClientRegisteredWithNotAdmin(ApiTester $I)
    {
        $I = $this->api->setHeadersWithAuthorization($I);
        $I->comment('------------------ Start -------------------');
        $I->sendDELETE($this->api->getUrl().'/clientregister/'.$this->responseData['privatekey']);
        $I->seeResponseCodeIs(403);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"Forbidden"');
        $I->seeResponseContains('"detail"');
        $I->comment('------------------ End ---------------------');    
    }
    
    public function checkDeleteClientRegisteredWithAdmin(ApiTester $I)
    {
        $I = $this->api->setHeadersWithAdminAuthorization($I);
        $I->comment('------------------ Start -------------------');
        $I->sendDELETE($this->api->getUrl().'/clientregister/'.$this->responseData['privatekey']);
        $I->seeResponseCodeIs(204);
        $I->dontSeeResponseContains('"Forbidden"');
        $I->dontSeeResponseContains('"detail"');
        $I->dontSeeResponseContains('{');
        $I->dontSeeInDatabase('app_register', ['privatekey' => $this->responseData['privatekey']]);        
        $I->comment('------------------ End ---------------------');    
    }    
        
}
