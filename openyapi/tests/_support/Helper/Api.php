<?php
namespace Helper;
// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Util\Fixtures;

class Api extends \Codeception\Module
{
    private $adminAuthorizationBearer   = 'Bearer 79222c5976553dd03061b635db327a73d71e634c';
    private $authorizationBearer        = 'Bearer a53f39d478c723e8bcf98d20f2376748bc570bb2';
    private $authorizationBasic         = 'Basic b3Blbnk6b3B5XzE=';
    
    public function setHeadersWithoutAuthorization($I)
    {
        $I->comment('I set this headers');
        $I->haveHttpHeader('Accept','application/hal+json');
        $I->haveHttpHeader('Content-Type','application/json');
        
        return $I;
    }
    
    public function setHeadersWithAuthorization($I)
    {
        $I->comment('I set this headers');
        $I->haveHttpHeader('Accept','application/hal+json');
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('Authorization', $this->authorizationBearer);
    
        return $I;
    }
    
    public function setHeadersWithAdminAuthorization($I)
    {
        $I->comment('I set this headers');
        $I->haveHttpHeader('Accept','application/hal+json');
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('Authorization', $this->adminAuthorizationBearer);
    
        return $I;
    }
    
    
    protected function getFixtures($value)
    {
        return Fixtures::get($value);
    }
    
    public function getUrl()
    {
        $url = $this->getFixtures('url');
        return $url;
    }
    
    public function getData($value)
    {
       $data = $this->getFixtures($value);
       return $data; 
    }
    
    public function cleanUp()
    {
        /*
        DELETE FROM `oauth_refresh_tokens` WHERE user_id = 'suombuel@gmail.com';
        DELETE FROM `oauth_access_tokens` WHERE user_id = 'suombuel@gmail.com';
        DELETE FROM `oauth_users` WHERE `username` IN ('suombuel@gmail.com');
        DELETE FROM `oauth_register` WHERE `email` IN ('suombuel@gmail.com');
        DELETE FROM `app_register` WHERE osversion ='Codeception ApiTest';
        */
    }

}
