<?php
/*
index.php:
This is the main page. It switch every page of the website.
In this page I setup the not-logged user details and I create every page sending data to template.

Fantamanager

To Do:
-Require meta.lang.php
-Setup sessions


Included library:
 * Savant2.php that add the library for the template system
 * config.inc.php that contain the general configuration of the website
 * dblib.inc.php that defines database access function
 * authlib.inc.php that includes function to define the authorization
 * langlib.inc.php that defines functions for lang array

*/
/*
 * Prevent XSS attach
 */

foreach($_POST as $key=>$val)
{
	if(is_array($val))
	{
		foreach($val as $key2=>$val2)
			$_POST[$key][$key2] = stripslashes(addslashes(htmlspecialchars($val2)));
	}
	else
		$_POST[$key] = stripslashes(addslashes(htmlspecialchars($val)));
}	
foreach($_GET as $key=>$val)
{
	if(is_array($val))
	{
		foreach($val as $key2=>$val2)
			$_GET[$key][$key2] = stripslashes(addslashes(htmlspecialchars($val2)));
	}
	else
		$_GET[$key] = stripslashes(addslashes(htmlspecialchars($val)));
}
require('config/config.inc.php');
require('config/Savant3.php');
require('config/pages.inc.php');
require(INCDIR . 'db.inc.php');
require(INCDIR . 'dbTable.inc.php');
require(INCDIR . 'strings.inc.php');
require(INCDIR . 'links.inc.php');
require(INCDIR . 'message.inc.php');
require(INCDIR . 'logger.inc.php');
require(INCDIR . "fb.php");

$p = new FirePHP();
$p->registerErrorHandler("E_WARNING");
$p->registerExceptionHandler();


//Creating a new db istance
$dbObj = new db();
$linksObj = new links();
$message = new message();
$logger = new logger();

//Creating object for pages
$layoutTpl = new Savant3(array('template_path' => TPLDIR));
$headerTpl = new Savant3(array('template_path' => TPLDIR));
$headerTpl->assign('linksObj',$linksObj);
$footerTpl = new Savant3(array('template_path' => TPLDIR));
$footerTpl->assign('linksObj',$linksObj);
$contentTpl = new Savant3(array('template_path' => TPLDIR));
$contentTpl->assign('linksObj',$linksObj);
$navbarTpl = new Savant3(array('template_path' => TPLDIR));
$navbarTpl->assign('linksObj',$linksObj);
$operationTpl = new Savant3(array('template_path' => OPERATIONTPLDIR));
$operationTpl->assign('linksObj',$linksObj);

//If no page have been required give the default page (home.php and home.tpl.php)
if (isset($_GET['p']))
	$p = $_GET['p'];
else
	$p = 'home';

$session_name = 'fantamanajer';
@session_name($session_name);
// strictly, PHP 4 since 4.4.2 would not need a verification
if (version_compare(PHP_VERSION, '5.1.2', 'lt') && isset($_COOKIE[$session_name]) && eregi("\r|\n", $_COOKIE[$session_name])) 
	die('attacked');

if (!isset($_COOKIE[$session_name])) 
{
	ob_start();
	$old_display_errors = ini_get('display_errors');
	$old_error_reporting = error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$r = session_start();
	ini_set('display_errors', $old_display_errors);
	error_reporting($old_error_reporting);
	unset($old_display_errors, $old_error_reporting);
	$session_error = ob_get_contents();
	ob_end_clean();
	if ($r !== TRUE || ! empty($session_error)) 
	{
		setcookie($session_name, '', 1);
		die('sessionError');
	}
}
else
	@session_start();
	
if(LOCAL || $_SESSION['roles'] == 2)
	define("DEBUG",TRUE);
else
	define("DEBUG",FALSE);

ob_start();
//Try login if POSTDATA exists
require_once(CODEDIR . 'login.code.php');

if(isset($_POST['username']) && $_SESSION['logged'])
	header('Location: ' . str_replace('&amp;','&',$linksObj->getLink('dettaglioSquadra',array('squadra'=>$_SESSION['idSquadra']))));

//Setting up the default user data
if (!isset($_SESSION['logged'])) {
	$_SESSION['userid'] = 1000;
	$_SESSION['roles'] = -1;
	$_SESSION['usertype'] = 'guest';
	$_SESSION['logged'] = FALSE;
	$_SESSION['idSquadra'] = FALSE;
	$_SESSION['idLega'] = 1;
	$_SESSION['legaView'] = 1;
}

require(INCDIR . 'lega.db.inc.php');
$legaObj = new lega();

/**
 * SETTO NEL CONTENTTPL LA GIORNATA
 */
require(INCDIR . 'giornata.db.inc.php');

$giornataObj = new giornata();
$giornata = $giornataObj->getGiornataByDate();
define("GIORNATA",$giornata['idGiornata']);
define("PARTITEINCORSO",$giornata['partiteInCorso']);
define("STAGIONEFINITA",$giornata['stagioneFinita']);


$leghe = $legaObj->getLeghe();
$layoutTpl->assign('leghe',$leghe);
if(!isset($_SESSION['legaView']))
	$_SESSION['legaView'] = $leghe[0]->idLega;
if(isset($_POST['legaView']))
	$_SESSION['legaView'] = $_POST['legaView'];
/**
 * INIZIALIZZAZIONE VARIABILI CONTENT
 * Questo Switch discrimina tra i vari moduli di codice quello che deve
 * essere caricato per visualizzare la pagina corretta
 *
 */
if(!isset($pages[$p])) 
{
	$message->error("La pagina " . $p . " non esiste. Sei stato mandato alla home");
	$p = 'home';
}
elseif($pages[$p]['roles'] > $_SESSION['roles']) 
{
	$message->error("Non hai l'autorizzazione necessaria per vedere la pagina " . strtolower($pages[$p]['title']) . ". Sei stato mandato alla home");
	$p = 'home';
}
if(isset($_SESSION['message']))
{
	$message = $_SESSION['message'];	
	unset($_SESSION['message']);
}
if(DEBUG)
	FB::group($p . '.code.php');
//INCLUDE IL FILE DI CODICE PER LA PAGINA
if (file_exists(CODEDIR . $p . '.code.php'))
	require(CODEDIR . $p . '.code.php');
//definisce il file di template utilizzato per visualizzare questa pagina
$tplfile = $p . '.tpl.php';
if(DEBUG)
	FB::groupEnd();
$layoutTpl->assign('message',$message);


/**
 * Eseguo i controlli per sapere se ci sono messaggi da comunicare all'utente e setto in sessione i dati di lega
 */
if(DEBUG)
	FB::group("Giocatori trasferiti");
if ($_SESSION['logged'])
{
	require_once(INCDIR . 'giocatore.db.inc.php');
	require_once(INCDIR . 'trasferimento.db.inc.php');
	
	$giocatoreObj = new giocatore();
	$trasferimentoObj = new trasferimento();
	$_SESSION['datiLega'] = $legaObj->getLegaById($_SESSION['idLega']);
	if($giocatoreObj->getGiocatoriTrasferiti($_SESSION['idSquadra']) != FALSE && count($trasferimentoObj->getTrasferimentiByIdSquadra($_SESSION['idSquadra'])) < $_SESSION['datiLega']->numTrasferimenti )
		$layoutTpl->assign('generalMessage','Un tuo giocatore non è più nella lista! Vai alla pagina trasferimenti');
}
if(DEBUG)
	FB::groupEnd();

//ASSEGNO ALLA NAVBAR LA PAGINA IN CUI SIAMO
$navbarTpl->assign('p',$p);
$navbarTpl->assign('pages',$pages);
/**
 *
 * INIZIALIZZAZIONE VARIABILI HEAD (<html><head>...</head><body>
 *
 */
$layoutTpl->assign('title',$pages[$p]['title']);
$layoutTpl->assign('p',$p);
if(isset($pages[$p]['css']))
 	$layoutTpl->assign('css', $pages[$p]['css']);
if(isset($pages[$p]['js']))
	$layoutTpl->assign('js', $pages[$p]['js']);

/**
 * GENERAZIONE LAYOUT
 */

/**
 * PRODUZIONE HEADER
 * il require include il file con il codice per l'header, incluso il nome del file template
 */
$header = $headerTpl->fetch('header.tpl.php');

/**
 * PRODUZIONE FOOTER
 * il require include il file con il codice per il'footer, incluso il nome del file del file template
 */
//$footertpl->assign('p',$p);
$footer = $footerTpl->fetch('footer.tpl.php');

/**
 * PRODUZIONE MENU
 * il require include il file con il codice per il menu, incluso il nome del file del file template
 */

// $navbarTpl->assign('p',$p);
$navbar = $navbarTpl->fetch('navbar.tpl.php');
/**
 * PRODUZIONE CONTENT
 * Esegue la fetch del template per l'area content
 */
$content = $contentTpl->fetch($tplfile);
$operation = "";
if($_SESSION['logged'])
	$operation .= $operationTpl->fetch(TPLDIR . "operazioni.tpl.php");
if(file_exists(OPERATIONTPLDIR . $p . ".tpl.php"))
	$operation .= $operationTpl->fetch($p . ".tpl.php");

/**
 * COMPOSIZIONE PAGINA
 */

$layoutTpl->assign('header', $header);
$layoutTpl->assign('footer', $footer);
$layoutTpl->assign('content', $content);
$layoutTpl->assign('operation', $operation);
$layoutTpl->assign('navbar', $navbar);

ob_end_clean();
/**
 * Output Pagina
 */
$layoutTpl->setFilters(array("Savant3_Filter_trimwhitespace","filter"));
$result = $layoutTpl->display('layout.tpl.php');
// now test the result of the display() call.  if there was an
// error, this will tell you all about it.
if ($layoutTpl->isError($result)) {
	echo "There was an error displaying the template. <pre>";
	print_r($result,1);
	echo "</pre>";
}
?>
