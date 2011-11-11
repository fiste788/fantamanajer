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
date_default_timezone_set("Europe/Rome");

require('config/config.inc.php');
require('config/pages.inc.php');
require(INCDIR . 'request.inc.php');
require(INCDIR . 'Savant.inc.php');
require(INCDIR . 'links.inc.php');
require(INCDIR . 'quickLinks.inc.php');
require(INCDIR . 'message.inc.php');
require(INCDIR . 'logger.inc.php');
require(INCDIR . 'ruolo.inc.php');
require(INCDIR . 'FirePHPCore/FirePHP.class.php');
require(INCDBDIR . 'db.inc.php');
require(TABLEDIR . 'dbTable.inc.php');
require(INCDBDIR . 'lega.db.inc.php');
require(INCDBDIR . 'giornata.db.inc.php');

//Creating a new db istance
$dbConnection = new db();
$message = new message();
$logger = new logger();
$request = new Request();
$quickLinks = new QuickLinks($request);

$ruoli = array();
$ruoli['P'] = new Ruolo("Portiere","Portieri","POR");
$ruoli['D'] = new Ruolo("Difensore","Difensori","DIF");
$ruoli['C'] = new Ruolo("Centrocampista","Centrocampisti","CEN");
$ruoli['A'] = new Ruolo("Attaccante","Attaccanti","ATT");

$generalJs = array();
//$generalJs[] = 'font/font.js';
$generalJs[] = 'jquery/jquery.js';
$generalJs[] = 'ui/jquery.effects.core.js';
$generalJs[] = 'ui/jquery.effects.pulsate.js';
$generalJs[] = 'uniform/jquery.uniform.js';
$generalJs[] = 'custom/all.js';

$generalCss = array();
$generalCss[] = 'boiler.css';
$generalCss[] = 'typography.css';
$generalCss[] = 'forms.css';
//$generalCss[] = 'buttons.css';
$generalCss[] = 'grid.css';
$generalCss[] = 'layout.css';
$generalCss[] = '00-screen.css';
$generalCss[] = 'uniform.css';

//Creating object for pages
$layoutTpl = new MySavant3(array('template_path' => TPLDIR));
$headerTpl = new MySavant3(array('template_path' => TPLDIR));
$footerTpl = new MySavant3(array('template_path' => TPLDIR));
$contentTpl = new MySavant3(array('template_path' => TPLDIR));
$navbarTpl = new MySavant3(array('template_path' => TPLDIR));
$operationTpl = new MySavant3(array('template_path' => OPERATIONTPLDIR));

$session_name = 'fantamanajer';
@session_name($session_name);

if (!isset($_COOKIE[$session_name])) {
	$r = session_start();
	if ($r !== TRUE) {
		setcookie($session_name, '', 1);
		die('sessionError');
	}
}
else
	@session_start();

//If no page have been required give the default page (home.php and home.tpl.php)
$p = $request->has('p') ? $request->get('p') : 'home';

define("DEBUG",(LOCAL || $_SESSION['roles'] == 2));
$firePHP = FirePHP::getInstance(TRUE);
$firePHP->setEnabled(DEBUG);
$firePHP->registerErrorHandler(FALSE);

ob_start();

//Try login if POSTDATA exists
require_once(CODEDIR . 'login.code.php');

if(isset($_POST['username']) && $_SESSION['logged'])
	header('Location: ' . str_replace('&amp;','&',Links::getLink('dettaglioSquadra',array('id'=>$_SESSION['idUtente']))));

//Setting up the default user data
if (!isset($_SESSION['logged'])) {
	$_SESSION['userid'] = 1000;
	$_SESSION['roles'] = -1;
	$_SESSION['usertype'] = 'guest';
	$_SESSION['logged'] = FALSE;
	$_SESSION['idUtente'] = FALSE;
	$_SESSION['legaView'] = 1;
}

/**
 * SETTO NEL CONTENTTPL LA GIORNATA
 */

$giornata = Giornata::getCurrentGiornata();

define("GIORNATA",$giornata['id']);
define("PARTITEINCORSO",$giornata['partiteInCorso']);
define("STAGIONEFINITA",$giornata['stagioneFinita']);


$leghe = Lega::getList();
$layoutTpl->assign('leghe',$leghe);
$contentTpl->assign('leghe',$leghe);
$navbarTpl->assign('leghe',$leghe);
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
	Request::send404();

if(isset($_SESSION['message']))
{
	$message = $_SESSION['message'];	
	unset($_SESSION['message']);
}

//INCLUDE IL FILE DI REQUEST PER LA PAGINA
if($request->has('submit') && file_exists(REQUESTDIR . $p . '.request.code.php')) {
    $firePHP->group($p . '.request.code.php');
	require(REQUESTDIR . $p . '.request.code.php');
	$firePHP->groupEnd();
}

$firePHP->group($p . '.code.php');
//INCLUDE IL FILE DI CODICE PER LA PAGINA
if (file_exists(CODEDIR . $p . '.code.php'))
	require(CODEDIR . $p . '.code.php');
$firePHP->groupEnd();

//definisce il file di template utilizzato per visualizzare questa pagina
$tplfile = $p . '.tpl.php';

$contentTpl->assign('request',$request);
$contentTpl->assign('ruoli',$ruoli);
$operationTpl->assign('request',$request);
$layoutTpl->assign('message',$message);
$layoutTpl->assign('quickLinks',$quickLinks);
/**
 * Eseguo i controlli per sapere se ci sono messaggi da comunicare all'utente e setto in sessione i dati di lega
 */
$firePHP->group("Giocatori trasferiti");
if ($_SESSION['logged'])
{
	require_once(INCDBDIR . 'giocatore.db.inc.php');
	require_once(INCDBDIR . 'trasferimento.db.inc.php');

	$_SESSION['datiLega'] = Lega::getById($_SESSION['idLega']);
	//if(Giocatore::getGiocatoriTrasferiti($_SESSION['idUtente']) != FALSE && count(Trasferimento::getTrasferimentiByIdSquadra($_SESSION['idUtente'])) < $_SESSION['datiLega']->numTrasferimenti )
	//	$layoutTpl->assign('generalMessage','Un tuo giocatore non è più nella lista! Vai alla pagina trasferimenti');
}
$firePHP->groupEnd();

$headerTpl->assign('dataFine',date_parse(Giornata::getTargetCountdown()->format("Y-m-d H:i:s")));
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
