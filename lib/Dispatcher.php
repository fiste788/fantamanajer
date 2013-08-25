<?php

namespace Lib;

class Dispatcher {

    /**
     *
     * @var String
     */
    public $controller;

    /**
     *
     * @var String
     */
    public $action;

    /**
     * 
     * @param \Lib\Request $request
     * @return \Lib\Response $response
     */
    public function handle(Request $request) {
        require_once('config' . DIRECTORY_SEPARATOR . 'routing.php');

        $response = new Response();
        $url = $request->getRequestUri();
        if (substr($url, -1) === '/') {
            $url = substr($url, 0, -1);
        }
        $route = $router->match($url);
        if ($route != FALSE) {
            $this->controller = $route['target']['controller'];
            $this->action = isset($route['target']['action']) ? $route['target']['action'] : 'index';
            foreach ($route['params'] as $key => $val) {
                $request->setParam($key, $val);
            }
            $controller = $this->getController($request, $response, $router, $route);
            $response->setBody($this->doAction($request,$controller));
        } else {
            $response->setHttpCode(500);
            die('route not found');
        }
        return $response;
    }

    /**
     * 
     * @return \Lib\BaseController
     */
    private function getController(Request $request, Response $response, $router, $route) {
        require_once('config' . DIRECTORY_SEPARATOR . 'static.php');
        $controllerName = '\Fantamanajer\Controllers\\' . ucfirst($this->controller) . "Controller";
        if (class_exists($controllerName)) {
            $controller = new $controllerName($request, $response);
            $controller->setRouter($router);
            $controller->setRoute($route);
            $controller->setGeneralJs($generalJs);
            $controller->setGeneralCss($generalCss);
            $controller->initialize();
            return $controller;
        } else {
            $response->setHttpCode(500);
            die('unsopported controller');
        }
    }

    private function doAction(Request $request,BaseController $controller) {
        $action = $this->action;
        $formatAction = "";
        if($request->getParam("format") != null) {
            $format = substr ($request->getParam ("format"), 1);
            $formatAction = $action . "_" . $format;
        }
        if (method_exists($controller, $formatAction)) {
            $controller->setFormat($format);
            $controller->$formatAction();
            return $controller->render();
        } else {
            if (method_exists($controller, $this->action)) {
                $controller->$action();
                return $controller->render();
            } else {
                die('unsopported method');
            }
        }
    }

}

