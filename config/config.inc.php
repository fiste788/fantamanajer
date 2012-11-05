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

if (end($tmp) == 'ajax')
    array_pop($tmp);
$sitepath = implode('/', $tmp);                      //recreate $sitepath with slash (/one/jpg)

define("SESSION_NAME", 'fantamanajer');
define("SESSION_TIMEOUT", 30);
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
        define("DBHOST", "leonardo.ldn.kgix.net:3306");

        define("MODREWRITE", TRUE);
        error_reporting(E_ALL);
    } else {
        define("DBTYPE", "mysql");
        define("DBNAME", "fantamanajer");
        define("DBUSER", "fantamanajerUser");
        define("DBPASS", "banana");
        define("DBHOST", "mysql13.aziendeitalia.com:3306");

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
    define("DBNAME", "fantamanajer-vuoto");
    define("DBUSER", "fantamanajerUser");
    define("DBPASS", "banana");
    define("DBHOST", "localhost");

    define("MODREWRITE", FALSE);
    error_reporting(E_ALL);
}

define("CSSDIR", FULLSTATICPATH . 'css/');
define("LESSDIR", CSSDIR . 'less/');
define("CACHEDIR", CSSDIR . 'cache/');
define("IMGSDIR", FULLSTATICPATH . 'imgs/');
define("PLAYERSDIR", IMGSDIR . 'foto/');
define("CLUBSDIR", IMGSDIR . 'clubs/');
define("UPLOADDIR", FULLSTATICPATH . 'uploadimg/');
define("DBDIR", FULLSTATICPATH . 'db/');
define("DOCSDIR", FULLSTATICPATH . 'docs/');
define("VOTIDIR", DOCSDIR . 'voti/');
define("VOTICSVDIR", VOTIDIR . 'csv/');
define("VOTIXMLDIR", VOTIDIR . 'xml/');
define("LOGSDIR", FULLSTATICPATH . 'logs/');
define("TMPDIR", sys_get_temp_dir() . '/');

define("JSDIR", FULLPATH . 'js/');
define("CODEDIR", FULLPATH . 'code/');
define("REQUESTDIR", CODEDIR . 'request/');
define("TPLDIR", FULLPATH . 'tpl/');
define("INCDIR", FULLPATH . 'inc/');
define("INCDBDIR", INCDIR . 'db/');
define("TABLEDIR", INCDBDIR . 'table/');
define("MODELDIR", TABLEDIR . 'model/');
define("VIEWDIR", INCDBDIR . 'view/');
define("AJAXDIR", 'ajax/');
define("MAILTPLDIR", TPLDIR . 'mail/');
define("OPERATIONTPLDIR", TPLDIR . 'operazioni/');

define("CSSURL", FULLSTATICURL . 'css/');
define("IMGSURL", FULLSTATICURL . 'imgs/');
define("EMOTICONSURL", IMGSURL . 'emoticons/');
define("PLAYERSURL", IMGSURL . 'foto/');
define("CLUBSURL", IMGSURL . 'clubs/');
define("UPLOADURL", FULLSTATICURL . 'uploadimg/');
define("DBURL", FULLSTATICURL . 'db/');
define("DOCSURL", FULLSTATICURL . 'docs/');
define("VOTIURL", DOCSURL . 'voti/csv/');

define("JSURL", FULLURL . 'js/');
define("AJAXURL", FULLURL . 'ajax/');

?>
