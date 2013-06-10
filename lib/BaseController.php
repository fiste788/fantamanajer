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
    protected $format = 'html';

    /**
     *
     * @var array
     */
    protected $route;

    protected $pages = array();

    /**
     *
     * @var \AltoRouter
     */
    protected $router;


    protected $generalJs = array();
    protected $generalCss = array();

    public function __construct($controller, $action, $router, $route) {
        require(CONFIGDIR . 'pages.php');

        $this->pages = $pages;
        $this->auth = new Login();
        \FirePHP::getInstance(TRUE);
        \FirePHP::getInstance()->setEnabled(LOCAL);
        \FirePHP::getInstance()->registerErrorHandler(FALSE);
        $this->controller = $controller;
        $this->action = $action;
        $this->router = $router;
        $this->route = $route;
        $this->request = Request::getInstance();
        \Lib\Router::getInstance($router);

        if(isset($route['params']['format']))
            $this->format = $route['params']['format'];

        if(isset($pages->pages[$route['name']]) && $pages->pages[$route['name']]->roles > $_SESSION['roles'])
            $this->notAuthorized();

        $this->templates['layout'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['header'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['navbar'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['footer'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['content'] = new \Savant3(array('template_path' => VIEWSDIR));
    }

    public abstract function initialize();

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

    public function renderAction($action) {
        $this->action = $action;
        $this->$action();
    }

    public function renderJson($json) {
        header("Content-Type: application/json; charset=UTF-8");
        echo $json;
        die();
    }

    public function redirectTo($routeName, array $params = array()) {
        header("Location: " . $this->router->generate($routeName,$params));
        die();
    }

    public function render() {
        $this->templates['layout']->assign('generalJs', $this->generalJs);
        $this->templates['layout']->assign('generalCss', $this->generalCss);
        $this->templates['layout']->assign('js',$this->pages->pages[$this->route['name']]->js);

        if ($this->format == 'html') {
            $content = $this->templates['content']->fetch($this->controller . DS . $this->action . '.php');
            $header = $this->templates['header']->fetch('header.tpl.php');
            $footer = $this->templates['footer']->fetch('footer.tpl.php');
            $navbar = $this->templates['navbar']->fetch('navbar.tpl.php');

            $this->templates['layout']->assign('header', $header);
            $this->templates['layout']->assign('footer', $footer);
            $this->templates['layout']->assign('content', $content);
            $this->templates['layout']->assign('navbar', $navbar);

            foreach ($this->fetched as $name => $content)
                $this->templates['layout']->assign($name, $content);

            $this->templates['layout']->setFilters(array("Savant3_Filter_trimwhitespace", "filter"));
            $this->templates['layout']->display('layout.tpl.php');
        } elseif($this->format == 'json') {

        }
        unset($_SESSION['__flash']);
    }

}

?>