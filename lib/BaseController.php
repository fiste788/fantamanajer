<?php

namespace Lib;

require_once(LIBDIR . 'Savant/Savant3.php');

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
    protected $pages = array();

    /**
     *
     * @var \Assetic\AssetManager
     */
    protected $asset = NULL;

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
        $this->asset = new \Assetic\AssetManager();
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

    public function setFlash($level, $message) {
        $_SESSION['__flash'] = (object) array('level' => $level, 'text' => $message);
    }

    public function urlFor($routeName, array $params = array()) {
        return $this->router->generate($routeName, $params);
    }

    public function setFormat($format) {
        $this->format = $format;
    }

    public function getFormat() {
        return $this->format;
    }

    public function setGeneralJs($generalJs) {
        $this->generalJs = $generalJs;
    }

    public function setGeneralCss($generalCss) {
        $less = new \lessc();
        $less->setVariables(array("imgs-path"=>IMGSURL));
        foreach ($generalCss as $key => $val) {
            $file = strpos($val, "/") ? substr($val, strpos($val, "/") + 1) : $val;
            $lessFile = LESSDIR . $val . ".less";
            $cssFile = STYLESHEETSDIR . $file . ".css";
            $less->checkedCompile($lessFile, $cssFile);
            $this->generalCss[$key] = $file . '.css';
        }
        $lessFile = LESSDIR . 'pages' . DS . $this->route['name'] . '.less';
        \FirePHP::getInstance()->info($lessFile);
        if(file_exists($lessFile)) {
            $cssFile = STYLESHEETSDIR . $this->route['name'] . ".css";
            \FirePHP::getInstance()->info($cssFile);
            $less->checkedCompile($lessFile, $cssFile);
            $this->generalCss[$key] = $this->route['name'] . '.css';
        }
        /*$files = \Fantamanajer\Lib\FileSystem::getFileIntoFolder(LESSDIR . 'pages');
        \FirePHP::getInstance()->log($files);
        foreach ($files as $file) {
            $less_fname = LESSDIR . $val . ".less";
            $css_fname = STYLESHEETSDIR . $file . ".css";
            $less->checkedCompile($less_fname, $css_fname);
            $this->generalCss[$key] = $file . '.css';
        }*/
    }

    public function renderAction($routeName, $method = 'GET') {
        $url = $this->router->generate($routeName);
        $route = $this->router->match($url, $method);
        if ($route['target']['controller'] == $this->controller) {
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
        $this->response->setBody(file_get_contents("404.html"));
        $this->response->sendResponse();
    }

    public function redirectTo($routeName, array $params = array()) {
        $this->response->setHeader("Location", $this->router->generate($routeName, $params), true);
        $this->response->sendResponse();
    }

    public function render($content = NULL) {
        $this->templates['layout']->assign('generalJs', $this->generalJs);
        $this->templates['layout']->assign('generalCss', $this->generalCss);
        if (isset($this->pages->pages[$this->route['name']])) {
            $this->templates['layout']->assign('js', $this->pages->pages[$this->route['name']]->js);
        }

        if ($content == NULL) {
            $contentFile = $this->controller . DS . $this->action . '.php';
            $content = file_exists(VIEWSDIR . $contentFile) ? $this->templates['content']->fetch($contentFile) : "";
        }

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

}

