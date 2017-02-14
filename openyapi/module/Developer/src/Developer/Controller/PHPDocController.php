<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Developer for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Developer\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class PHPDocController extends AbstractActionController
{
    public function indexAction()
    {

        /*
         * Browser redirection (302 Temporary moved)
         * to phpdoc/index.html if not requested in url
         */
    	$file = $this->params()->fromRoute('file', FALSE);
        $phpdoc = $this->params()->fromRoute('phpdoc', FALSE);
        $url = $this->getRequest()->getRequestUri();
        if (FALSE === strpos($url,$file)){
            $this->redirect()->toUrl($phpdoc.'/'.preg_replace('/^\/+/','',$file))->setStatusCode(302);
            return;
        }

        /*
         * Response process:
         * Took the requested file if exist from /docs/developer/phpdoc
         * and serve its contents (html near all known cases) and serve it
         * otherwise a 404 error is returned
         */
        $response = $this->getResponse();

    	if($file){
            $file = APPLICATION_PATH."/docs/developer/phpdoc/".$file;
    	    if(file_exists($file)){
                $matches = [];
                /*
                 * Before serving the file contents, a replacement of resources sources is done,
                 * exchanging any "phpdoc relative" path with a "site relative" public path starting in "assets/phpdoc"
                 */
                $content = file_get_contents($file);
                $content = preg_replace('/(<link(?:(?!href).)* href="|(<img|<script) src=")(\.\.\/)?([^"]+)(")/', '${1}/assets/phpdoc/${4}"', $content);

                // Patch for @uses url on template "responsive"
                $content = preg_replace_callback('/(href="(?:(?!%5C)[^"])+(%5C(?:(?!%5C)[^"])+)+)"/', function($m){return str_replace("%5C",".",$m[1]).'.html"';},$content);

                $response->setContent($content);
                return $response;
            }
        }

        $this->getResponse()->setStatusCode(404);
        return;
    }


}
