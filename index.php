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
require(INCDIR . 'notify.inc.php');
require(INCDIR . 'logger.inc.php');
require(INCDIR . 'ruolo.inc.php');
require(INCDIR . 'FirePHPCore/FirePHP.class.php');
require(INCDBDIR . 'db.inc.php');
require(INCDBDIR . 'lega.db.inc.php');
require(INCDBDIR . 'giornata.db.inc.php');

//Creating a new db istance
$message = new message();
$request = Request::getInstance();
$logger = new logger();
$quickLinks = new QuickLinks($request);
$notifiche = array();

$ruoli = array();
$ruoli['P'] = new Ruolo("Portiere", "Portieri", "POR");
$ruoli['D'] = new Ruolo("Difensore", "Difensori", "DIF");
$ruoli['C'] = new Ruolo("Centrocampista", "Centrocampisti", "CEN");
$ruoli['A'] = new Ruolo("Attaccante", "Attaccanti", "ATT");

$generalJs = array();
//$generalJs[] = 'font/font.js';
$generalJs[] = 'jquery/jquery-1.9.0b1.js';
$generalJs[] = 'jquery/jquery-migrate-1.0.0b1.js';
$generalJs[] = 'syze/syze.js';
$generalJs[] = 'ui/jquery.ui.effect.js';
$generalJs[] = 'ui/jquery.ui.effect-pulsate.js';
//$generalJs[] = 'uniform/jquery.uniform.js';
$generalJs[] = 'bootstrap/bootstrap-transition.js';
$generalJs[] = 'bootstrap/bootstrap-collapse.js';
$generalJs[] = 'bootstrap/bootstrap-dropdown.js';
$generalJs[] = 'stickyPanel/jquery.stickyPanel.js';
//$generalJs[] = 'bootstrap/bootstrap-affix.js';
$generalJs[] = 'countdown/jquery.jcountdown1.3.js';
$generalJs[] = 'googleAnalytics/googleAnalytics.js';
$generalJs[] = 'custom/all.js';
//$generalJs[] = 'social/facebook.js';
//$generalJs[] = 'social/googleplus.js';

$generalCss = array();
//$generalCss[] = 'boiler.css';
//$generalCss[] = 'typography.css';
//$generalCss[] = 'forms.css';
//$generalCss[] = 'buttons.css';
//$generalCss[] = 'grid.css';
$generalCss[] = 'bootstrap/bootstrap';
$generalCss[] = 'bootstrap/responsive';
$generalCss[] = 'layout';
$generalCss[] = 'style';
$generalCss[] = 'pages';
$generalCss[] = 'fancybox';
$generalCss[] = 'fileupload';
//$generalCss[] = 'uniform.css';
//Creating object for pages
$layoutTpl = new MySavant3(array('template_path' => TPLDIR));
$headerTpl = new MySavant3(array('template_path' => TPLDIR));
$footerTpl = new MySavant3(array('template_path' => TPLDIR));
$contentTpl = new MySavant3(array('template_path' => TPLDIR));
$navbarTpl = new MySavant3(array('template_path' => TPLDIR));
$operationTpl = new MySavant3(array('template_path' => OPERATIONTPLDIR));

global $firePHP;
$firePHP = FirePHP::getInstance(TRUE);
$firePHP->setEnabled(LOCAL);
$firePHP->registerErrorHandler(FALSE);


ob_start();


require_once(CODEDIR . 'login.code.php');

define("DEBUG", (LOCAL || DEVELOP || $_SESSION['roles'] == 2));
$firePHP->setEnabled(DEBUG);

$p = Request::getInstance()->has('p') ? Request::getInstance()->get('p') : 'home';
if (!isset($pages->pages[$p]))
    Request::send404();
elseif ($pages->pages[$p]->roles > $_SESSION['roles']) {
    $message->error("Non hai l'autorizzazione necessaria");
    $p = 'home';
}

$currentGiornata = Giornata::getCurrentGiornata();

define("GIORNATA", $currentGiornata->getId());
define("STAGIONEFINITA", $currentGiornata->getStagioneFinita());

$leghe = Lega::getList();
if (isset($_POST['legaView']))
    $_SESSION['legaView'] = $_POST['legaView'];
$currentLega = Lega::getById($_SESSION['legaView']);

if (isset($_SESSION['idLega']))
    $_SESSION['datiLega'] = $leghe[$_SESSION['idLega']];

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

/**
 * INIZIALIZZAZIONE VARIABILI CONTENT
 * Questo Switch discrimina tra i vari moduli di codice quello che deve
 * essere caricato per visualizzare la pagina corretta
 */
$layoutTpl->assign('title', $pages->pages[$p]->title);
$firePHP->log(REQUESTDIR . $p . '.request.code.php');
//INCLUDE IL FILE DI REQUEST PER LA PAGINA
if (Request::getInstance()->has('submit') && file_exists(REQUESTDIR . $p . '.request.code.php')) {
    $firePHP->group($p . '.request.code.php');
    try {
        require(REQUESTDIR . $p . '.request.code.php');
    } catch (FormException $fe) {
        $message->warning($fe->getMessage());
    } catch (PDOException $e) {
        FirePHP::getInstance()->error($e->getMessage());
        FirePHP::getInstance()->error($e->getTraceAsString());
        $message->error("Errore generico salvataggio dati");
    }
     $firePHP->groupEnd();
}

//INCLUDE IL FILE DI CODICE PER LA PAGINA
if (file_exists(CODEDIR . $p . '.code.php')) {
    try {
        $firePHP->group($p . '.code.php');
        require(CODEDIR . $p . '.code.php');
        $firePHP->groupEnd();
    } catch(Exception $e) {
        FirePHP::getInstance()->error($e->getMessage());
        FirePHP::getInstance()->error($e->getTrace());
        Request::send500();
    }
}

if (LOCAL)
    require_once(CODEDIR . 'less2css.code.php');

require_once(CODEDIR . 'navbar.code.php');

$headerTpl->assign('dataFine', date_parse($currentGiornata->getData()->format("Y-m-d H:i:s")));
$headerTpl->assign('timestamp', $currentGiornata->getData()->getTimestamp());
$operationTpl->assign('request', $request);
$contentTpl->assign('request', $request);
$contentTpl->assign('ruoli', $ruoli);
$navbarTpl->assign('request', $request);
$navbarTpl->assign('notifiche', $notifiche);
$navbarTpl->assign('pages', $pages);
$navbarTpl->assign('leghe', $leghe);
$layoutTpl->assign('message', $message);
$layoutTpl->assign('quickLinks', $quickLinks);
$layoutTpl->assign('p', $p);
$layoutTpl->assign('generalJs', $generalJs);
$layoutTpl->assign('generalCss', $generalCss);
if (isset($pages->pages[$p]->css))
    $layoutTpl->assign('css', $pages->pages[$p]->css);
if (isset($pages->pages[$p]->js))
    $layoutTpl->assign('js', $pages->pages[$p]->js);
if (isset($pages->pages[$p]->ieHack))
    $layoutTpl->assign('ieHack', $pages->pages[$p]->ieHack);

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
$footer = $footerTpl->fetch('footer.tpl.php');

/**
 * PRODUZIONE MENU
 * il require include il file con il codice per il menu, incluso il nome del file del file template
 */
$navbar = $navbarTpl->fetch('navbar.tpl.php');
/**
 * PRODUZIONE CONTENT
 * Esegue la fetch del template per l'area content
 */
$tplfile = $p . '.tpl.php';
$content = $contentTpl->fetch($tplfile);
$operation = "";

//if($_SESSION['logged'])
//$operation .= $operationTpl->fetch(TPLDIR . "operazioni.tpl.php");
if (file_exists(OPERATIONTPLDIR . $p . ".tpl.php"))
    $operation .= $operationTpl->fetch($p . ".tpl.php");

/**
 * COMPOSIZIONE PAGINA
 */
$layoutTpl->assign('header', $header);
$layoutTpl->assign('footer', $footer);
$layoutTpl->assign('content', $content);
$layoutTpl->assign('operation', $operation);
$layoutTpl->assign('navbar', $navbar);

if (($ob = ob_get_contents()) != "")
    $firePHP->warn($ob);

/**
 * Output Pagina
 */
$layoutTpl->setFilters(array("Savant3_Filter_trimwhitespace", "filter"));
$result = $layoutTpl->display('layout.tpl.php');
// now test the result of the display() call.  if there was an
// error, this will tell you all about it.
if ($layoutTpl->isError($result))
    echo "There was an error displaying the template. <pre>" . print_r($result, 1) . "</pre>";
?>
