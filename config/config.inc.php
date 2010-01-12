<?php
$proto = "http://";						//protocol
$host = $_SERVER['SERVER_NAME'];				//server name

$hostArray = explode('.',$host);
$local = (substr($_SERVER['REMOTE_ADDR'],0,7) == '192.168' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == 'localhost') ? TRUE:FALSE;

$tmp = explode('/',$_SERVER['PHP_SELF']);			//website path(example: for "http://www.dominio.it/one/jpg/one.jpg" it takes "/one/jpg/one.jpg")
array_pop($tmp);						//delete the last field of $tmp array (1 => one, 2=> jpg)
$sitepath = implode('/',$tmp);					//recreate $sitepath with slash (/one/jpg)

if (isset($_SERVER['DOCUMENT_ROOT']))
	$doc_root = $_SERVER['DOCUMENT_ROOT'];			//document root specified into php.ini file

$cwd = str_replace('\\','/',getcwd());				//get currently used directory(if under windows replace \\ with /)
$doc_root = str_replace($sitepath,'',$cwd);			//example /var/www/

define ("LOCAL",$local);

if(!LOCAL)
{
	if($hostArray[0] != 'www')
		array_unshift($hostArray, 'www');
	$hostStaticArray = $hostArray;
	$hostStaticArray[0] = 'static';
	$hostStatic = implode('.',$hostStaticArray);
	$host = implode('.',$hostArray);
	$array = explode("/",FULLPATH);
	array_pop($array);
	array_pop($array);
	
	define("FULLPATH",$doc_root . $sitepath . '/');
	define("FULLURL",$proto . $host . $sitepath . '/');
	define("FULLSTATICURL",$proto . $hostStatic . $sitepath . '/');
	define("FULLSTATICPATH",implode("/",$array) . "/subdomains/static/httpdocs/");
	
	define("DBTYPE","mysql");
	define("DBNAME","fantamanajer");
	define("DBUSER","fantamanajerUser");
	define("DBPASS","banana");
	define("DBHOST","mysql13.aziendeitalia.com:3306");
	
	define("MODREWRITE",TRUE);
	define("DEBUG",FALSE);
	error_reporting(0);
}
else
{
	define("FULLPATH",$doc_root . $sitepath . '/');
	define("FULLURL",$proto . $host . $sitepath . '/');
	define("FULLSTATICURL",FULLURL);
	define("FULLSTATICPATH",FULLPATH);
	
	define("DBTYPE","mysql");
	define("DBNAME","fantamanajer");
	define("DBUSER","fantamanajer");
	define("DBPASS","banana");
	define("DBHOST","localhost");
	
	define("MODREWRITE",FALSE);
	define("DEBUG",TRUE); 
	error_reporting(E_ALL);
}

define("CSSDIR",FULLSTATICPATH . 'css/');
define("IMGSDIR",FULLSTATICPATH . 'imgs/');
define("PLAYERSDIR",IMGSDIR . 'foto/');
define("CLUBSDIR",IMGSDIR . 'clubs/');
define("UPLOADDIR",FULLSTATICPATH . 'uploadimg/');
define("DBDIR",FULLSTATICPATH . 'db/');
define("DOCSDIR",FULLSTATICPATH . 'docs/');
define("VOTIDIR",DOCSDIR . 'voti/csv/');
define("TMPDIR",'/tmp/');

define("JSDIR",'js/');
define("CODEDIR",'code/');
define("TPLDIR",'tpl/');
define("INCDIR",'inc/');
define("MAILTPLDIR",TPLDIR . 'mail/');
define("OPERATIONTPLDIR",TPLDIR . 'operation/');

define("CSSURL",FULLSTATICURL . 'css/');
define("IMGSURL",FULLSTATICURL . 'imgs/');
define("EMOTICONSURL",IMGSURL . 'emoticons/');
define("PLAYERSURL",IMGSURL . 'foto/');
define("CLUBSURL",IMGSURL . 'clubs/');
define("UPLOADURL",FULLSTATICURL . 'uploadimg/');
define("DBURL",FULLSTATICURL . 'db/');
define("DOCSURL",FULLSTATICURL . 'docs/');
define("VOTIURL",DOCSURL . 'voti/csv/');

define("JSURL",FULLURL . 'js/');
?>
