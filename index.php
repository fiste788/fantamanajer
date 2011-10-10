<?php
/*
index.php:
This is the main page. It switch every page of the website.
In this page I setup the not-logged user details and I create every page sending data to template.

Fantamanager

To Do:
-Require meta.lang.php

*/

/*
 * Prevent XSS attach
 */

function preventAttach($array) 
{
	foreach($array as $key=>$val) 
	{
		if(is_array($val))
			$_array[$key][] = preventAttach($val);
		else
			$_array[$key] = stripslashes(addslashes(htmlspecialchars($val)));
	}
	return $array;
}
$_GET = preventAttach($_GET);
$_POST = preventAttach($_POST);

require('config/config.inc.php');
require('config/pages.inc.php');
require(INCDIR . 'savant/Savant3.php');
require(INCDIR . 'db.inc.php');
require(INCDIR . 'links.inc.php');
require(INCDIR . 'message.inc.php');
require(INCDIR . 'logger.inc.php');
require(INCDIR . 'FirePHPCore/FirePHP.class.php');
require(TABLEDIR . 'dbTable.inc.php');
require(INCDBDIR . 'lega.db.inc.php');
require(INCDBDIR . 'giornata.db.inc.php');

//Creating a new db istance
$dbObj = new db();
$message = new message();
$logger = new logger();

$generalJs = array();
//$generalJs[] = 'font/font.js';
$generalJs[] = 'jquery/jquery.js';
$generalJs[] = 'ui/jquery.effects.core.js';
$generalJs[] = 'ui/jquery.effects.pulsate.js';
$generalJs[] = 'uniform/jquery.uniform.js';
$generalJs[] = 'custom/all.js';

$generalCss = array();
$generalCss[] = '00-screen.css';
$generalCss[] = 'uniform.css';

//Creating object for pages
$layoutTpl = new Savant3(array('template_path' => TPLDIR));
$headerTpl = new Savant3(array('template_path' => TPLDIR));
$footerTpl = new Savant3(array('template_path' => TPLDIR));
$contentTpl = new Savant3(array('template_path' => TPLDIR));
$navbarTpl = new Savant3(array('template_path' => TPLDIR));
$operationTpl = new Savant3(array('template_path' => OPERATIONTPLDIR));

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


//If no page have been required give the default page (home.php and home.tpl.php)
$p = isset($_GET['p']) ? $_GET['p'] : 'home';

define("DEBUG",(LOCAL || $_SESSION['roles'] == 2));
$firePHP = FirePHP::getInstance(TRUE);
$firePHP->setEnabled(DEBUG);
$firePHP->registerErrorHandler(FALSE);

ob_start();

//Try login if POSTDATA exists
require_once(CODEDIR . 'login.code.php');

if(isset($_POST['username']) && $_SESSION['logged'])
	header('Location: ' . str_replace('&amp;','&',Links::getLink('dettaglioSquadra',array('squadra'=>$_SESSION['idUtente']))));

//Setting up the default user data
if (!isset($_SESSION['logged'])) {
	$_SESSION['userid'] = 1000;
	$_SESSION['roles'] = -1;
	$_SESSION['usertype'] = 'guest';
	$_SESSION['logged'] = FALSE;
	$_SESSION['idUtente'] = FALSE;
	$_SESSION['idLega'] = 1;
	$_SESSION['legaView'] = 1;
}

/**
 * SETTO NEL CONTENTTPL LA GIORNATA
 */

$giornata = Giornata::getGiornataByDate();
define("GIORNATA",$giornata['id']);
define("PARTITEINCORSO",$giornata['partiteInCorso']);
define("STAGIONEFINITA",$giornata['stagioneFinita']);


$leghe = Lega::getList();
$layoutTpl->assign('leghe',$leghe);
if(!isset($_SESSION['legaView']))
	$_SESSION['legaView'] = $leghe[1]->id;
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

$firePHP->group($p . '.code.php');
//INCLUDE IL FILE DI CODICE PER LA PAGINA
if (file_exists(CODEDIR . $p . '.code.php'))
	require(CODEDIR . $p . '.code.php');
//definisce il file di template utilizzato per visualizzare questa pagina
$tplfile = $p . '.tpl.php';

$firePHP->groupEnd();
$layoutTpl->assign('message',$message);

/**
 * Eseguo i controlli per sapere se ci sono messaggi da comunicare all'utente e setto in sessione i dati di lega
 */
$firePHP->group("Giocatori trasferiti");
if ($_SESSION['logged'])
{
	require_once(INCDIR . 'giocatore.db.inc.php');
	require_once(INCDIR . 'trasferimento.db.inc.php');

	$_SESSION['datiLega'] = $leghe($_SESSION['idLega']);
	//if(Giocatore::getGiocatoriTrasferiti($_SESSION['idUtente']) != FALSE && count(Trasferimento::getTrasferimentiByIdSquadra($_SESSION['idUtente'])) < $_SESSION['datiLega']->numTrasferimenti )
	//	$layoutTpl->assign('generalMessage','Un tuo giocatore non è più nella lista! Vai alla pagina trasferimenti');
}
$firePHP->groupEnd();

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
$layoutTpl->assign('generalJs',$generalJs);
$layoutTpl->assign('generalCss',$generalCss);
if(isset($pages[$p]['css']))
 	$layoutTpl->assign('css', $pages[$p]['css']);
if(isset($pages[$p]['js']))
	$layoutTpl->assign('js', $pages[$p]['js']);
if(isset($pages[$p]['ieHack']))
	$layoutTpl->assign('ieHack', $pages[$p]['ieHack']);

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

$ob = ob_get_contents();
if($ob != "")
	$firePHP->warn($ob);
	
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
