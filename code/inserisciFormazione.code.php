<?php
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "lega.db.inc.php");
require_once(INCDBDIR . "giocatore.db.inc.php");
require_once(INCDBDIR . "punteggio.db.inc.php");
require_once(INCDBDIR . "voto.db.inc.php");
require_once(INCDIR . 'mail.inc.php');

$mailContent = new Savant3();

$filterUtente = Request::getInstance()->has('idUtente') ? Request::getInstance()->get('idUtente') : NULL;
$filterGiornata = Request::getInstance()->has('idGiornata') ? Request::getInstance()->get('idGiornata') : NULL;
$filterLega = Request::getInstance()->has('idLega') ? Request::getInstance()->get('idLega') : NULL;
$filterModulo = Request::getInstance()->has('modulo') ? Request::getInstance()->get('modulo') : NULL;
if($_SESSION['usertype'] == 'admin')
	$filterLega = $_SESSION['idLega'];

$ruoliKey = array('P','D','C','A');
$ruo = array('P'=>'Portiere','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');
$elencocap = array('C','VC','VVC');

if($filterLega != NULL) {
	$squadre = Utente::getByField('idLega',$filterLega);
	$operationTpl->assign('elencosquadre',$squadre);
}

$formazione = Formazione::getFormazioneBySquadraAndGiornata($filterUtente,$filterGiornata);

$giocatori = Giocatore::getGiocatoriBySquadraAndGiornata($filterUtente,$filterGiornata);
$contentTpl->assign('giocatori',$giocatori);


if($filterModulo != NULL)
	$modulo = explode('-',$filterModulo);
else
	$modulo = NULL;
	
$firePHP->log($filterLega);

$elencoLeghe = Lega::getList();
$contentTpl->assign('elencoleghe',$elencoLeghe);

if($formazione != FALSE)
	$contentTpl->assign('formazione',$formazione);
$contentTpl->assign('lega',$filterLega);
$contentTpl->assign('mod',$filterModulo);
$contentTpl->assign('modulo',$modulo);
$contentTpl->assign('giornata',$filterGiornata);
$contentTpl->assign('squadra',$filterUtente);
$contentTpl->assign('ruo',$ruo);
$contentTpl->assign('ruoliKey',$ruoliKey);
$contentTpl->assign('elencocap',$elencocap);
$operationTpl->assign('elencoleghe',$elencoLeghe);
$operationTpl->assign('lega',$filterLega);
$operationTpl->assign('mod',$filterModulo);
$operationTpl->assign('giornata',$filterGiornata);
$operationTpl->assign('squadra',$filterUtente);
?>
