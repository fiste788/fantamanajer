<?php

$proto = "http://";      //protocol
$host = $_SERVER['SERVER_NAME'];    //server name

$hostArray = explode('.', $host);
$local = (substr($_SERVER['REMOTE_ADDR'], 0, 7) == '192.168' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == 'localhost');
$develop = ($hostArray[0] == 'develop');

$tmp = explode('/', $_SERVER['PHP_SELF']);   //website path(example: for "http://www.dominio.it/one/jpg/one.jpg" it takes "/one/jpg/one.jpg")
array_pop($tmp);      //delete the last field of $tmp array (1 => one, 2=> jpg)

if (isset($_SERVER['DOCUMENT_ROOT']))
    $doc_root = $_SERVER['DOCUMENT_ROOT'];   //document root specified into php.ini file

$cwd = str_replace('\\', '/', getcwd());    //get currently used directory(if under windows replace \\ with /)
$doc_root = str_replace(implode('/', $tmp), '', $cwd);   //example /var/www/

$sitepath = implode('/', $tmp);                      //recreate $sitepath with slash (/one/jpg)

define("DS","/");
define("SESSION_NAME", 'fantamanajer');
define("SESSION_TIMEOUT", 60);
define("PROTO", $proto);
define("LOCAL", $local);
define("DEVELOP", $develop);
define("MAILFROM", "stefano.sonzogni@fantamanajer.it");
define("MAILUSERNAME", "stefano.sonzogni@fantamanajer.it");
define("MAILPASSWORD", "monitor88");

if (!LOCAL) {
    $host = implode('.', $hostArray);
    $hostStaticArray = $hostArray;
    /* if($hostStaticArray[0] == 'www')
      $hostStaticArray[0] = 'static';
      elseif($hostStaticArray[0] != 'develop')
      array_unshift($hostStaticArray, 'static');
     */
    $hostStatic = implode('.', $hostStaticArray);
    $array = explode("/", $doc_root);
    array_pop($array);

    define("FULLPATH", $doc_root . $sitepath . '/');
    define("FULLURL", $proto . $host . $sitepath . '/');
    define("FULLBASEURL", $proto . $host);
    define("FULLURLAUTH", $proto . "administrator:banana@" . $host . $sitepath . '/');
    define("FULLSTATICURL", $proto . $hostStatic . $sitepath . '/');
    define("FULLSTATICURLAUTH", $proto . "administrator:banana@" . $hostStatic . $sitepath . '/');
    //define("FULLSTATICPATH",implode("/",$array) . "/subdomains/static/httpdocs/");
    define("FULLSTATICPATH", FULLPATH);

    if (!DEVELOP) {
        define("DBTYPE", "mysql");
        define("DBNAME", "fantaman_fantamanajer");
        define("DBUSER", "fantaman_user");
        define("DBPASS", "B@n@n@2");
        define("DBHOST", "localhost");

        define("MODREWRITE", TRUE);
        error_reporting(E_ALL);
    } else {
        define("DBTYPE", "mysql");
        define("DBNAME", "fantamanajer");
        define("DBUSER", "fantamanajerUser");
        define("DBPASS", "banana");
        define("DBHOST", "localhost");

        define("MODREWRITE", FALSE);
        error_reporting(E_ALL);
    }
} else {
    define("FULLPATH", $doc_root . $sitepath . '/');
    define("FULLURL", $proto . $host . (($_SERVER['SERVER_PORT'] != 80) ? ":" . $_SERVER['SERVER_PORT'] : '') . $sitepath . '/');
    define("FULLURLAUTH", $proto . "administrator:banana@" . $host . $sitepath . '/');
    define("FULLSTATICURL", FULLURL);
    define("FULLSTATICPATH", FULLPATH);

    define("DBTYPE", "mysql");
    define("DBNAME", "fantamanajer");
    define("DBUSER", "fantamanajerUser");
    define("DBPASS", "banana");
    define("DBHOST", "localhost");

    define("MODREWRITE", FALSE);
    error_reporting(E_ALL);
}

define("CONFIGDIR", FULLSTATICPATH . 'config' . DS);
define("PUBLICDIR", FULLSTATICPATH . 'public' . DS);
define("STYLESHEETSDIR", PUBLICDIR . 'stylesheets' . DS);
define("CSSDIR", STYLESHEETSDIR . 'css' . DS);
define("LESSDIR", STYLESHEETSDIR . 'less' . DS);
define("CACHEDIR", STYLESHEETSDIR . 'cache' . DS);
define("IMAGESDIR", PUBLICDIR . 'images' . DS);
define("PLAYERSDIR", IMAGESDIR . 'photo' . DS);
define("CLUBSDIR", IMAGESDIR . 'clubs' . DS);
define("UPLOADDIR", IMAGESDIR . 'upload' . DS);
define("DBDIR", FULLSTATICPATH . 'db/');
define("DOCSDIR", FULLSTATICPATH . 'docs/');
define("VOTIDIR", DOCSDIR . 'voti/');
define("VOTICSVDIR", VOTIDIR . 'csv/');
define("VOTIXMLDIR", VOTIDIR . 'xml/');
define("LOGSDIR", FULLSTATICPATH . 'logs/');
define("TMPDIR", sys_get_temp_dir() . '/');
define("JAVASCRIPTSDIR", PUBLICDIR . 'javascripts' . DS);

define("APPDIR",FULLPATH . 'app' . DS);
define("CONTROLLERSDIR", APPDIR . 'controllers' . DS);
define("VIEWSDIR", APPDIR . 'views' . DS);
define("MAILTPLDIR", VIEWSDIR . 'mails' . DS);
define("OPERATIONSDIR", VIEWSDIR . 'operations' . DS);
define("LAYOUTSDIR", VIEWSDIR . 'layouts' . DS);
define("MODELSDIR", APPDIR . 'models' . DS);
define("LIBDIR", FULLPATH . 'lib' . DS);
define("VENDORDIR", FULLPATH . 'vendor' . DS);
define("INCDIR", FULLPATH . 'inc' . DS);
define("AJAXDIR", 'ajax/');


define("PUBLICURL", FULLSTATICURL . 'public/');
define("CSSURL", PUBLICURL . 'stylesheets/');
define("IMGSURL", PUBLICURL . 'images/');
define("EMOTICONSURL", IMGSURL . 'emoticons/');
define("PLAYERSURL", IMGSURL . 'photo/');
define("CLUBSURL", IMGSURL . 'clubs/');
define("UPLOADURL", IMGSURL . 'upload/');
define("DBURL", FULLSTATICURL . 'db/');
define("DOCSURL", FULLSTATICURL . 'docs/');
define("VOTIURL", DOCSURL . 'voti/csv/');

define("JSURL", PUBLICURL . 'javascripts/');
define("AJAXURL", FULLURL . 'ajax/');

 