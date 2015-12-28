<?php

namespace Lib;

use AltoRouter;
use Assetic\Asset\AssetCache;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\AssetManager;
use Assetic\AssetWriter;
use Assetic\Cache\FilesystemCache;
use Assetic\Filter\ScssphpFilter;
use Exception;
use FirePHP;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Savant3;

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
     * @var Savant3[]
     */
    protected $templates = array();

    /**
     *
     * @var array
     */
    protected $fetched = array();

    /**
     *
     * @var Login
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
     * @var boolean
     */
    protected $noLayout = false;

    /**
     *
     * @var string
     */
    protected $title;
    
    /**
     *
     * @var string
     */
    protected $view;

    /**
     *
     * @var array
     */
    protected $route;

    /**
     *
     * @var Page[]
     */
    protected $pages = array();

    /**
     *
     * @var Page
     */
    protected $page = NULL;

    /**
     *
     * @var AssetManager
     */
    protected $asset = NULL;

    /**
     *
     * @var Logger
     */
    protected static $logger = NULL;

    /**
     *
     * @var AltoRouter
     */
    protected $router;
    protected $generalJs = array();
    protected $generalCss = array();

    public function __construct(Request $request, Response $response) {
        require(CONFIGDIR . 'pages.php');

        $this->pages = $pages;
        $this->auth = new Login();
        self::$logger = new Logger("logger");
        self::$logger->pushHandler(new StreamHandler(LOGSDIR . date("Ymd") . ".log"));
        //self::$logger->pushHandler(new \Monolog\Handler\FirePHPHandler());
        ErrorHandler::register(self::$logger);
        $this->asset = new AssetManager();
        FirePHP::getInstance(TRUE);
        FirePHP::getInstance()->setEnabled(LOCAL);
        FirePHP::getInstance()->registerErrorHandler(true);

        $this->request = $request;
        $this->response = $response;

        $this->templates['layout'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['header'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['navbar'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['footer'] = new \Savant3(array('template_path' => LAYOUTSDIR));
        $this->templates['content'] = new \Savant3(array('template_path' => VIEWSDIR));
    }

    public function initialize() {
        Router::getInstance($this->router);
        if (isset($this->pages->pages[$this->route['name']])) {
            if ($this->pages->pages[$this->route['name']]->roles > $_SESSION['roles']) {
                $this->notAuthorized();
            } else {
                $this->page = $this->pages->pages[$this->route['name']];
                $this->title = $this->page->title;
                
            }
        }
        /* else
          die("Pagina " . $this->route['name'] . " da inserire in pages.php"); */
        $this->view = $this->action;
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
        /* $less = new \lessc();
          //$less->setVariables(array("imgs-path"=>IMGSURL));
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
          } */


        //$fm = new FilterManager();
        //$fm->set('sass', new ScssphpFilter());

        $am = new AssetManager();


        $styles = new AssetCollection(array(
            new FileAsset(SCSSDIR . 'main.scss'),
                ), array(
            new ScssphpFilter()
        ));
        $styles->setTargetPath("main.css");

        $cache_css = new AssetCache($styles, new FilesystemCache(CACHEDIR));

        $am->set('base_css', $cache_css);
        $writer = new AssetWriter(CSSDIR);
        $writer->writeManagerAssets($am);
        $this->generalCss[] = 'main.css';
        /*
          $scss = new scssc();
          $scss->setImportPaths(SCSSDIR);
          foreach ($generalCss as $key => $val) {
          $file = strpos($val, "/") ? substr($val, strpos($val, "/") + 1) : $val;
          $scss_fname = $val . ".scss";
          $css_fname = STYLESHEETSDIR . $file . ".css";
          $cache_fname = CACHEDIR . $file . ".cache";
          //$cache = (file_exists($cache_fname)) ? unserialize(file_get_contents($cache_fname)) : $scss_fname;
          $new_cache = $scss->compile('@import "' . $scss_fname . '"');
          //if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
          //file_put_contents($cache_fname, serialize($new_cache));
          file_put_contents($css_fname, $new_cache);
          //}
          //lessc::ccompile($scss_fname, $css_fname);
          $this->generalCss[$key] = $file . '.css';
          }
          /*$less_fname = LESSDIR . 'pages' . DS . $this->route['name'] . '.less';
          \FirePHP::getInstance()->info($less_fname);
          if(file_exists($less_fname)) {
          $css_fname = STYLESHEETSDIR . $this->route['name'] . ".css";
          \FirePHP::getInstance()->info($css_fname);
          $cache_fname = CACHEDIR . $file . ".cache";
          $cache = (file_exists($cache_fname)) ? unserialize(file_get_contents($cache_fname)) : $less_fname;
          $new_cache = \lessc::cexecute($cache);
          if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
          file_put_contents($cache_fname, serialize($new_cache));
          file_put_contents($css_fname, $new_cache['compiled']);
          }
          \lessc::ccompile($less_fname, $css_fname);
          $this->generalCss[$key] = $this->route['name'] . '.css';
          } */
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
            new Exception("Cannot render action of a different controller");
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
        $this->templates['header']->assign('title', $this->title);
        $this->templates['layout']->assign('generalJs', $this->generalJs);
        $this->templates['layout']->assign('generalCss', $this->generalCss);
        if (isset($this->pages->pages[$this->route['name']])) {
            $this->templates['layout']->assign('js', $this->pages->pages[$this->route['name']]->js);
        }

        if ($content == NULL) {
            $contentFile = $this->controller . DS . $this->view . '.php';
            $content = file_exists(VIEWSDIR . $contentFile) ? $this->templates['content']->fetch($contentFile) : "";
        }

        if (!$this->noLayout) {
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
            $output = $content;
        }
        unset($_SESSION['__flash']);
        return $output;
    }

    public function getRouter() {
        return $this->router;
    }

    public function setRouter(AltoRouter $router) {
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
