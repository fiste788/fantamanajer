<?php

namespace Lib;

require_once(VENDORDIR . 'Savant/Savant3.php');
require_once(VENDORDIR . 'FirePHPCore/FirePHP.class.php');
//require_once(LIBDIR . 'Login.php');

abstract class BaseController {

    const FLASH_INFO = 0;
    const FLASH_SUCCESS = 1;
    const FLASH_NOTICE = 2;
    const FLASH_ERROR = 3;

    /**
     *
     * @var string
     */
    protected $controller;

    /**
     *
     * @var string
     */
    protected $action;

    /**
     *
     * @var \Savant3[]
     */
    protected $templates = array();

    /**
     *
     * @var array
     */
    protected $fetched = array();

    /**
     *
     * @var \Login
     */
    protected $auth;

    /**
     *
     * @var Request
     */
    protected $request;
    
    /**
     *
     * @var Response
     */
    protected $response;


    protected $format = 'html';

    /**
     *
     * @var array
     */
    protected $route;
    
    protected $body = "";

    protected $pages = array();
    
    /**
     *
     * @var \Logger
     */
    protected $logger = NULL;

    /**
     *
     * @var \AltoRouter
     */
    protected $router;


    protected $generalJs = array();
    protected $generalCss = array();

    public function __construct(Request $request, Response $response) {
        require(CONFIGDIR . 'pages.php');
        
        $this->pages = $pages;
        $this->auth = new Login();
        $this->logger = new Logger();
        \FirePHP::getInstance(TRUE);
        \FirePHP::getInstance()->setEnabled(LOCAL);
        \FirePHP::getInstance()->registerErrorHandler(false);
        
        $this->request = $request;
        $this->response = $response;

        $this->templates['layout'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['header'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['navbar'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['footer'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['content'] = new \Savant3(array('template_path' => VIEWSDIR));
    }

    public function initialize() {
        \Lib\Router::getInstance($this->router);
        if (isset($this->pages->pages[$this->route['name']]) && $this->pages->pages[$this->route['name']]->roles > $_SESSION['roles']) {
            $this->notAuthorized();
        }
    }

    public abstract function notAuthorized();

    public function setFlash($level,$message) {
        $_SESSION['__flash'] = (object) array('level'=>$level,'text'=>$message);
    }

    public function urlFor($routeName, array $params = array()) {
        return $this->router->generate($routeName, $params);
    }

    public function setFormat($format) {
        $this->format = $format;
    }

    public function setGeneralJs($generalJs) {
        $this->generalJs = $generalJs;
    }

    public function setGeneralCss($generalCss) {
        require_once(VENDORDIR . 'LessPHP/lessc.inc.php');

        foreach ($generalCss as $key => $val) {
            $file = strpos($val, "/") ? substr($val, strpos($val, "/") + 1) : $val;
            $less_fname = LESSDIR . $val . ".less";
            $css_fname = STYLESHEETSDIR . $file . ".css";
            $cache_fname = CACHEDIR . $file . ".cache";
            $cache = (file_exists($cache_fname)) ? unserialize(file_get_contents($cache_fname)) : $less_fname;
            $new_cache = \lessc::cexecute($cache);
            if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
                file_put_contents($cache_fname, serialize($new_cache));
                file_put_contents($css_fname, $new_cache['compiled']);
            }
            \lessc::ccompile($less_fname, $css_fname);
            $this->generalCss[$key] = $file . '.css';
        }
    }

    public function renderAction($routeName,$method = 'GET') {
        $url = $this->router->generate($routeName);
        $route = $this->router->match($url,$method); 
        if($route['target']['controller'] == $this->controller) {
            $action = $route['target']['action'];
            $this->route = $route;
            $this->action = $action;
            $this->initialize();
            $this->$action();
        } else {
            new \Exception("Cannot render action of a different controller");
        }
    }
    
    public function send404() {
        $this->response->setHttpCode(404);
        $this->response->sendResponse();
    }

    public function redirectTo($routeName, array $params = array()) {
        $this->response->setHeader("Location", $this->router->generate($routeName,$params), true);
        $this->response->sendResponse();
    }

    public function render() {
        if($this->format == "json") {
            $this->response->setContentType ( "application/json" );
        }
        if(empty($this->body)) {
            $this->templates['layout']->assign('generalJs', $this->generalJs);
            $this->templates['layout']->assign('generalCss', $this->generalCss);
            $this->templates['layout']->assign('js',$this->pages->pages[$this->route['name']]->js);
        
            $content = $this->templates['content']->fetch($this->controller . DS . $this->action . '.php');
            $header = $this->templates['header']->fetch('header.php');
            $footer = $this->templates['footer']->fetch('footer.php');
            $navbar = $this->templates['navbar']->fetch('navbar.php');

            $this->templates['layout']->assign('header', $header);
            $this->templates['layout']->assign('footer', $footer);
            $this->templates['layout']->assign('content', $content);
            $this->templates['layout']->assign('navbar', $navbar);

            foreach ($this->fetched as $name => $content) {
                $this->templates['layout']->assign($name, $content);
            }
            $this->templates['layout']->setFilters(array("Savant3_Filter_trimwhitespace", "filter"));
           
            $output = $this->templates['layout']->fetch('layout.php');
            
        } else {
            $output = $this->getBody();
        }
        unset($_SESSION['__flash']);
        return $output;
    }
    
    public function getRouter() {
        return $this->router;
    }

    public function setRouter(\AltoRouter $router) {
        $this->router = $router;
    }

    public function getRoute() {
        return $this->route;
    }

    public function setRoute($route) {
        $this->route = $route;
        $this->controller = $route['target']['controller'];
        $this->action = $route['target']['action'];
    }

    public function getBody() {
        return $this->body;
    }

    public function setBody($body) {
        $this->body = $body;
    }



}

 