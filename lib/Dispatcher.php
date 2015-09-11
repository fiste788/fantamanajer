<?php

namespace Lib;

use Exception;
use FirePHP;

class Dispatcher {

    /**
     *
     * @var string
     */
    public $controller;

    /**
     *
     * @var string
     */
    public $action;

    /**
     * 
     * @param Request $request
     * @return Response $response
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
            $content = "";
            try {
                $controller = $this->getController($request, $response, $router, $route);
                if(!is_null($controller)) {
                    try {
                        ob_start();
                        $this->doAction($request, $controller);
                        $body = ob_get_clean();
                        if ($body != "") {
                            FirePHP::getInstance()->info("Presente un output diretto. Evito il rendering del controller");
                            $content = $body;
                        } else {
                            $content = $controller->render();
                        }
                    } catch (Exception $ex) {
                        ob_end_clean();
                        FirePHP::getInstance()->error($ex->getMessage());
						//die($ex);
                        $content = $controller->render("Si è verificato un errore interno nell'elaborazione dei dati");
                        $response->setHttpCode(500);
                    }
                    if ($controller->getFormat() == "json") {
                        $response->setContentType("application/json");
                    }
                } else {
                    $response->setHttpCode(404);
                    $response->setBody(file_get_contents("404.html"));
                }
            } catch (Exception $e) {
                $response->setHttpCode(500);
                $content = $e->getMessage();
            }
            $response->setBody($content);
        } else {
            $response->setHttpCode(404);
            $response->setBody(file_get_contents("404.html"));
        }
        return $response;
    }

    /**
     * 
     * @return BaseController
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
        }
    }

    private function doAction(Request $request, BaseController $controller) {
        $action = $this->action;
        $formatAction = "";
        if ($request->getParam("format") != null) {
            $format = substr($request->getParam("format"), 1);
            $formatAction = $action . "_" . $format;
        }
        if (method_exists($controller, $formatAction)) {
            $action = $formatAction;
            $controller->setFormat($format);
        } else {
            if (method_exists($controller, $this->action)) {
                $action = $this->action;
            } else {
                die('unsopported method');
            }
        }
        $controller->$action();
    }

}

