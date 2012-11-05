<?php
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'formazione.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');

$dettaglio = Giocatore::getVotiGiocatoriByGiornataAndSquadra(Request::getInstance()->get('giornata'),Request::getInstance()->get('squadra'));
$formazione = Formazione::getFormazioneBySquadraAndGiornata(Request::getInstance()->get('squadra'),Request::getInstance()->get('giornata'));
if($dettaglio == FALSE && $formazione == FALSE)
	Request::send404();

$utente = Utente::getById(Request::getInstance()->get('squadra'));
$maxGiornate = Punteggio::getGiornateWithPunt();
for($i = 1;$i <= $maxGiornate;$i++)
	$giornate[$i] = $i;

if($dettaglio != FALSE)
	$titolari = array_splice($dettaglio,0,11);
else
	$titolari = FALSE;

$quickLinks->set('giornata',$giornate,"",array('squadra'=>Request::getInstance()->get('squadra')));

$contentTpl->assign('somma',$utente->getPunteggioByGiornata(Request::getInstance()->get('giornata')));
$contentTpl->assign('titolari',$titolari);
$contentTpl->assign('panchinari',$dettaglio);
$contentTpl->assign('penalità',Punteggio::getPenalitàBySquadraAndGiornata(Request::getInstance()->get('squadra'),Request::getInstance()->get('giornata')));
$contentTpl->assign('squadraDett',$utente);
$operationTpl->assign('squadre',$currentLega->getUtenti());
$operationTpl->assign('giornate',$giornate);
?>
