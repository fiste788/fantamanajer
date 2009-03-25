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

require_once 'config/config.inc.php';
require_once 'config/Savant2.php';
require_once 'config/pages.inc.php';
require_once INCDIR.'db.inc.php';
require_once INCDIR.'auth.inc.php';
require_once INCDIR.'strings.inc.php';
require_once INCDIR.'links.inc.php';



//Creating a new db istance
$dbLink = &new db;
$dbLink->dbConnect();

//Creating object for pages
$layouttpl =& new Savant2();
$headertpl =& new Savant2();
$footertpl =& new Savant2();
$contenttpl =& new Savant2();
$navbartpl =& new Savant2();

//Creating linksObj in object pages
$linksObj =& new links();
$headertpl->assign('linksObj',$linksObj);
$footertpl->assign('linksObj',$linksObj);
$contenttpl->assign('linksObj',$linksObj);
$navbartpl->assign('linksObj',$linksObj);

//If no page have been required give the default page (home.php and home.tpl.php)
if (isset($_GET['p']))
	$p = $_GET['p'];
else
	$p = 'home';


//Adding the language

if (!isset($_SESSION['lang']))
	$_SESSION['lang'] = 'it';

/*
require_once(LANGDIR.$_SESSION['lang'].'/general.lang.php');
$sesslang = $_SESSION['lang'];
*/

//Try login if POSTDATA exists
require_once(CODEDIR.'login.code.php');

if(isset($_POST['username']) && $_SESSION['logged'] == TRUE)
	header('Location: '. str_replace('&amp;','&',$linksObj->getLink('rosa',array('squadra'=>$_SESSION['idSquadra']))));

//Setting up the default user data
if (!isset($_SESSION['logged'])) {
	$_SESSION['userid'] = 1000;
	$_SESSION['usertype'] = 'guest';
	$_SESSION['logged'] = FALSE;
	$_SESSION['idSquadra'] = FALSE;
	$_SESSION['idLega'] = 1;
}

/**
 * SETTO NEL CONTENTTPL LA GIORNATA
 */
require_once(INCDIR.'giornata.inc.php');
$giornataObj = new giornata();
$timeout = $giornataObj->getIdGiornataByDate();
$giornata = $timeout;
if($timeout == FALSE)
	$giornata = $giornataObj->getIdGiornataByDateSecondary();	
else 
	$timeout = TRUE;
if($giornata > ($giornataObj->getNumberGiornate()-1))
	$timeout = -1;

define("GIORNATA",$giornata);
define("TIMEOUT",$timeout);
$contenttpl->assign('giornata',GIORNATA);
$contenttpl->assign('timeout',TIMEOUT);
	
/**
 * Eseguo i controlli per sapere se ci sono messaggi da comunicare all'utente e setto in sessione i dati di lega
 */

if ($_SESSION['logged'])
{
	require_once(INCDIR.'giocatore.inc.php');
	require_once(INCDIR.'trasferimenti.inc.php');
	require_once(INCDIR.'leghe.inc.php');
	$giocatoreObj = new giocatore();
	$trasferimentiObj = new trasferimenti();
	$legheObj = new leghe();
	$_SESSION['datiLega'] = $legheObj->getLegaById($_SESSION['idLega']);
	if($giocatoreObj->getGiocatoriTrasferiti($_SESSION['idSquadra']) != FALSE && count($trasferimentiObj->getTrasferimentiByIdSquadra($_SESSION['idSquadra'])) < $_SESSION['datiLega']['numTrasferimenti'] )
		$contenttpl->assign('generalMessage','Un tuo giocatore non è più nella lista! Vai alla pagina trasferimenti');
}

/**
 * INIZIALIZZAZIONE VARIABILI CONTENT
 * Questo Switch discrimina tra i vari moduli di codice quello che deve
 * essere caricato per visualizzare la pagina corretta
 *
 */
$adminpages = array_merge($adminpages,$userpages);
$superadminpages = array_merge($superadminpages,$adminpages);
if ($_SESSION['logged'] == TRUE && $_SESSION['usertype'] == "superadmin")
{
	if(array_key_exists($p,$superadminpages))
	{
		if (file_exists(CODEDIR.$p.'.code.php'))			//Including code file for this page
			require(CODEDIR.$p.'.code.php');
		$tplfile = TPLDIR.$p.'.tpl.php';				//Definition of template file
	}
	else
	{
		$_SESSION['message'][0] = 1;
		$_SESSION['message'][1] = "La pagina " . $p . " non esiste. Sei stato mandato alla home";
		$p = 'home';
		//INCLUDE IL FILE DI CODICE PER LA PAGINA
		if (file_exists(CODEDIR.$p.'.code.php'))
				require(CODEDIR.$p.'.code.php');
		//definisce il file di template utilizzato per visualizzare questa pagina
		$tplfile = TPLDIR.$p.'.tpl.php';
	}
	$layouttpl->assign('pages',$superadminpages[$p]);
}
elseif ($_SESSION['logged'] == TRUE && $_SESSION['usertype'] == "admin")
{
	if(array_key_exists($p,$adminpages))
	{
		if (file_exists(CODEDIR.$p.'.code.php'))			//Including code file for this page
			require(CODEDIR.$p.'.code.php');
		$tplfile = TPLDIR.$p.'.tpl.php';				//Definition of template file
	}
	else
	{
		if(array_key_exists($p, $superadminpages))
		{
			$_SESSION['message'][0] = 0;
			$_SESSION['message'][1] = "È necessario essere amministratori di sistema per vedere la pagina " . strtolower($superadminpages[$p]['title']) . ". Sei stato mandato alla home";
		}
		else
		{
			$_SESSION['message'][0] = 1;
			$_SESSION['message'][1] = "La pagina " . $p . " non esiste. Sei stato mandato alla home";
		}
		$p = 'home';
		//INCLUDE IL FILE DI CODICE PER LA PAGINA
		if (file_exists(CODEDIR.$p.'.code.php'))
				require(CODEDIR.$p.'.code.php');
		//definisce il file di template utilizzato per visualizzare questa pagina
		$tplfile = TPLDIR.$p.'.tpl.php';
	}
	$layouttpl->assign('pages',$adminpages[$p]);
}
elseif ($_SESSION['logged'] == TRUE)
{
	$_SESSION['import']=0;
	if(array_key_exists($p,$userpages))
	{
		if (file_exists(CODEDIR.$p.'.code.php'))			//Including code file for this page
			require(CODEDIR.$p.'.code.php');
		$tplfile = TPLDIR.$p.'.tpl.php';				//Definition of template file
	}
	else
	{
		if(array_key_exists($p, $superadminpages))
		{
			$_SESSION['message'][0] = 0;
			$_SESSION['message'][1] = "È necessario essere amministratori di sistema per vedere la pagina " . strtolower($superadminpages[$p]['title']) . ". Sei stato mandato alla home";
		}
		elseif(array_key_exists($p, $adminpages))
		{
			$_SESSION['message'][0] = 0;
			$_SESSION['message'][1] = "È necessario essere amministratori per vedere la pagina " . strtolower($adminpages[$p]['title']) . ". Sei stato mandato alla home";
		}
		else
		{
			$_SESSION['message'][0] = 1;
			$_SESSION['message'][1] = "La pagina " . $p . " non esiste. Sei stato mandato alla home";
		}
		$p = 'home';
		//INCLUDE IL FILE DI CODICE PER LA PAGINA
		if (file_exists(CODEDIR.$p.'.code.php'))
				require(CODEDIR.$p.'.code.php');
		//definisce il file di template utilizzato per visualizzare questa pagina
		$tplfile = TPLDIR.$p.'.tpl.php';
	}
	$layouttpl->assign('pages',$userpages[$p]);
}
else
{
	if(array_key_exists($p,$guestpages))
	{
		if (file_exists(CODEDIR.$p.'.code.php'))			//Including code file for this page
			require(CODEDIR.$p.'.code.php');
		$tplfile = TPLDIR.$p.'.tpl.php';				//Definition of template file
	}
	else
	{
		if(array_key_exists($p, $superadminpages))
		{
			$_SESSION['message'][0] = 0;
			$_SESSION['message'][1] = "È necessario loggarsi per vedere la pagina " . strtolower($superadminpages[$p]['title']) . ". Sei stato mandato alla home";
		}
		else
		{
			$_SESSION['message'][0] = 1;
			$_SESSION['message'][1] = "La pagina " . $p . " non esiste. Sei stato mandato alla home";
		}
		$p = 'home';
		//INCLUDE IL FILE DI CODICE PER LA PAGINA
		if (file_exists(CODEDIR.$p.'.code.php'))
			require(CODEDIR.$p.'.code.php');
		//definisce il file di template utilizzato per visualizzare questa pagina
		$tplfile = TPLDIR.$p.'.tpl.php';
	}
	$layouttpl->assign('pages',$guestpages[$p]);
}
//ASSEGNO ALLA NAVBAR LA PAGINA IN CUI SIAMO
$navbartpl->assign('p',$p);

/**
 *
 * INIZIALIZZAZIONE VARIABILI HEAD (<html><head>...</head><body>
 *
 */
// $header->assign('title',$lang['title']);
// $layouttpl->assign('styles', $styles);
// $layouttpl->assign('meta', $lang['description']);
// $layouttpl->assign('meta', $lang['keywords']);
// $layouttpl->assign('js', $js);

/**
 * GENERAZIONE LAYOUT
 */

/**
 * PRODUZIONE HEADER
 * il require include il file con il codice per l'header, incluso il nome del file template
 */
$header=$headertpl->fetch(TPLDIR.'header.tpl.php');

/**
 * PRODUZIONE FOOTER
 * il require include il file con il codice per il'footer, incluso il nome del file del file template
 */
//$footertpl->assign('p',$p);
$footer=$footertpl->fetch(TPLDIR.'footer.tpl.php');

/**
 * PRODUZIONE MENU
 * il require include il file con il codice per il menu, incluso il nome del file del file template
 */

// $navbartpl->assign('p',$p);
$navbar=$navbartpl->fetch(TPLDIR.'navbar.tpl.php');
/**
 * PRODUZIONE CONTENT
 * Esegue la fetch del template per l'area content
 */
$content=$contenttpl->fetch($tplfile);

/**
 * COMPOSIZIONE PAGINA
 */

$layouttpl->assign('header', $header);
$layouttpl->assign('footer', $footer);
$layouttpl->assign('content', $content);
$layouttpl->assign('navbar', $navbar);

/**
 * Output Pagina
 */

$result = $layouttpl->display(TPLDIR.'layout.tpl.php');
// now test the result of the display() call.  if there was an
// error, this will tell you all about it.
if ($layouttpl->isError($result)) {
	echo "There was an error displaying the template. <pre>";
	print_r($result,1);
	echo "</pre>";
}

$dbLink->dbClose();
//echo "<pre>".print_r($_SESSION,1)."</pre>";
?>
