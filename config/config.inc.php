<?php
$proto = "http://";						//protocol
$host = $_SERVER['SERVER_NAME'];				//server name

$hostArray = explode('.',$host);
if( substr($_SERVER['REMOTE_ADDR'],0,7) == '192.168' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == 'localhost')
	define ("LOCAL",TRUE);
else
{
	if($hostArray[0] == 'fantamanajer')
		array_unshift($hostArray, 'static');
	$hostArray[0] = 'static';
	define ("LOCAL",FALSE);
}
$host = implode('.',$hostArray);
define('DEBUG',FALSE);

$tmp = explode('/',$_SERVER['PHP_SELF']);			//website path(example: for "http://www.test.us/one/jpg/one.jpg" it takes "/one/jpg/one.jpg")
array_pop($tmp);						//delete the last field of $tmp array (1 => one, 2=> jpg)
$sitepath = implode('/',$tmp);					//recreate $sitepath with slash (/one/jpg)

if (isset($_SERVER['DOCUMENT_ROOT']))
	$doc_root = $_SERVER['DOCUMENT_ROOT'];			//document root specified into php.ini file


$cwd = str_replace('\\','/',getcwd());				//get currently used directory(if under windows replace \\ with /)
$doc_root = str_replace($sitepath,'',$cwd);			//example c:/xammp/htdocs

define ("FULLPATH",$doc_root . $sitepath . '/');			//fullpath example: c:/xammp/htdocs/sportravelanguage/config/
define ("FULLURL",$proto . $host . $sitepath . '/');			//fullurl example: http://localhost/sportravelanguage/config/

								//absolute paths for:
if(!LOCAL)
{
	$array = explode("/",FULLPATH);
	array_pop($array);
	array_pop($array);
	define("FULLSTATICPATH",implode("/",$array) . "/subdomains/static/httpdocs/");
}
else
	define("FULLSTATICPATH",FULLPATH);
define ("CSSDIR",FULLSTATICPATH . 'css/');				//css => CSSDIR
define ("JSDIR",FULLSTATICPATH . 'js/');				//js => JSDIR
define ("IMGDIR",FULLSTATICPATH . 'imgs/');				//img => IMGDIR
define ("UPLOADDIR",FULLSTATICPATH . 'uploadimg/');		//uploadimg => UPLOADDIR
								//relative paths for:
define ("CODEDIR",'code/');					//code => CODEDIR
define ("TPLDIR",'tpl/');					//tpl => TPLDIR
define ("INCDIR",'inc/');					//inc => INCDIR
define ("DBDIR",'db/');				//admincode => ADMINCODEDIR
define ("MAILTPLDIR",TPLDIR . 'mail/');
define ("VOTIDIR",'docs/voti/csv/');				 //docs/voti => VOTIDIR
define ("DOCSDIR",'docs/');
define ("TMPDIR",'tmp/');				 //docs/voti => VOTIDIR

define ("CSSURL",FULLURL.'css/');				//css => CSSURL
define ("JSURL",FULLURL.'js/');					//js => JSURL
define ("IMGSURL",FULLURL.'imgs/');				//img => IMGSURL
define ("UPLOADIMGURL",FULLURL.'uploadimg/');			//uploadimg => UPLOADIMGURL

if( substr($_SERVER['REMOTE_ADDR'],0,7) == '192.168' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == 'localhost')
{
	//database access:
	define ("DBTYPE","mysql");					//database type => DBTYPE
	define ("DBNAME","fantamanajer");					//database name => DBNAME
	define ("DBUSER","fantamanajer");					//database username => DBUSER
	define ("DBPASS","banana");						//database password => DBPASS
	define ("DBHOST","localhost");					//database host => DBHOST
	//modrewrite
	define ("MODREWRITE",FALSE);
	// Comunica gli errori semplici di esecuzione 
	error_reporting(E_ALL);					//activation of error reporting(to be disactivated at the end of the script)
}
else
{
	define ("DBTYPE","mysql");					//database type => DBTYPE
	define ("DBNAME","devfantamanajer");					//database name => DBNAME
	define ("DBUSER","developer");					//database username => DBUSER
	define ("DBPASS","banana");						//database password => DBPASS
	define ("DBHOST","mysql13.aziendeitalia.com:3306");					//database host => DBHOST
	define ("MODREWRITE",TRUE);
	error_reporting(0);
}
?>
