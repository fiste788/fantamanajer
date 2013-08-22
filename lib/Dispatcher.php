<?php

namespace Lib;

class Dispatcher {

    /**
     * 
     * @param \Lib\Request $request
     * @return \Lib\Response $response
     */
    public function handle(Request $request) {
        require_once('config' . DIRECTORY_SEPARATOR . 'static.php');
        require_once('config' . DIRECTORY_SEPARATOR . 'routing.php');
        
        /*Response::getResponse();
        Response::getResponse()->setBody("aaa");
        Response::getResponse()->sendResponse();*/
        $response = new Response();

        $url = $request->getRequestUri();
        if (substr($url, -1) === '/')
            $url = substr($url, 0, -1);
        $match = $router->match($url);
        if ($match != FALSE) {

            $controller = $match['target']['controller'];
            $action = isset($match['target']['action']) ? $match['target']['action'] : 'index';
            $controllerName = '\Fantamanajer\Controllers\\' . ucfirst($controller) . "Controller";
//echo "<pre>" . print_r($pages,1) . "</pre>";


            if (class_exists($controllerName)) {
                //FirePHP::getInstance()->log($controllerName);
                $loader = new $controllerName($request,$response);
                $loader->setRouter($router);
                $loader->setRoute($match);
                $loader->setGeneralJs($generalJs);
                $loader->setGeneralCss($generalCss);
                $loader->initialize();
                
                if (method_exists($controllerName, $action)) {
                   
           
                    $loader->$action();
                    $content = $loader->render();
        
                     $response->setBody($content);
                     
                    //$response->sendResponse();
                   // echo "<pre>" . print_r($response,1) . "</pre>";
                   //return $response;
                } else
                    die('unsopported method');
            } else {
                die('unsopported controller');
            }
        } else
            die('route not found');
        // 
       return $response;
    }

}

 