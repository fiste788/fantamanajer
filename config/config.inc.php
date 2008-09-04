<?php
/*
config.inc.php:
Config is a configuration file with definitions, database connection parameters and error notification.

FantaManajer
To do:
*/

$proto = "http://";						//protocol
$host = $_SERVER['SERVER_NAME'];				//server name

$tmp = explode('/',$_SERVER['PHP_SELF']);			//website path(example: for "http://www.test.us/one/jpg/one.jpg" it takes "/one/jpg/one.jpg")
array_pop($tmp);						//delete the last field of $tmp array (1 => one, 2=> jpg)
$sitepath = implode('/',$tmp);					//recreate $sitepath with slash (/one/jpg)

if (isset($_SERVER['DOCUMENT_ROOT']))
  $doc_root = $_SERVER['DOCUMENT_ROOT'];			//document root specified into php.ini file


$cwd = str_replace('\\','/',getcwd());				//get currently used directory(if under windows replace \\ with /)
$doc_root = str_replace($sitepath,'',$cwd);			//example c:/xammp/htdocs

define ("FULLPATH",$doc_root.$sitepath.'/');			//fullpath example: c:/xammp/htdocs/sportravelanguage/config/
define ("FULLURL",$proto.$host.$sitepath.'/');			//fullurl example: http://localhost/sportravelanguage/config/

								//absolute paths for:
define ("CSSDIR",FULLPATH.'css/');				//css => CSSDIR
define ("JSDIR",FULLPATH.'js/');				//js => JSDIR
define ("IMGDIR",FULLPATH.'img/');				//img => IMGDIR

								//relative paths for:
define ("CODEDIR",'code/');					//code => CODEDIR
define ("TPLDIR",'tpl/');					//tpl => TPLDIR
define ("LANGDIR",'lang/');					//lang => LANGDIR
define ("INCDIR",'inc/');					//inc => INCDIR
define ("TMPUPLOAD",'tmpupl/');					//tmpupl => TMPUPLOAD
define ("UPLOADDIR",'uploadimg/');				//uploadimg => UPLOADDIR
define ("ADMINCODEDIR",'admincode/');				//admincode => ADMINCODEDIR
define ("ADMINTPLDIR",'admintpl/');				//admintpl => ADMINTPLDIR

								//urls for:
define ("CSSURL",FULLURL.'css/');				//css => CSSURL
define ("JSURL",FULLURL.'js/');					//js => JSURL
define ("RSSURL",FULLURL.'rss/');					//rss => RSSURL
define ("IMGSURL",FULLURL.'imgs/');				//img => IMGSURL
define ("UPLOADIMGURL",FULLURL.UPLOADDIR);			//uploadimg => UPLOADIMGURL

if( substr($_SERVER['REMOTE_ADDR'],0,7) == '192.168' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' )
{
	//database access:
	define ("DBTYPE","mysql");					//database type => DBTYPE
	define ("DBNAME","test");					//database name => DBNAME
	define ("DBUSER","ingo_fm");					//database username => DBUSER
	define ("DBPASS","banana");						//database password => DBPASS
	define ("DBHOST","localhost");					//database host => DBHOST
	
	// Comunica gli errori semplici di esecuzione 
	error_reporting(E_ALL);					//activation of error reporting(to be disactivated at the end of the script)
}
else
{
	define ("DBTYPE","mysql");					//database type => DBTYPE
	define ("DBNAME","fantamanajer");					//database name => DBNAME
	define ("DBUSER","fantamanajerUser");					//database username => DBUSER
	define ("DBPASS","banana");						//database password => DBPASS
	define ("DBHOST","mysql13.aziendeitalia.com:3306");					//database host => DBHOST
	
	error_reporting(0);
}	
?>
