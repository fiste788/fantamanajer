<?php 
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'formazione.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');

$dettaglio = Giocatore::getVotiGiocatoriByGiornataAndSquadra($request->get('giornata'),$request->get('squadra'));
$formazione = Formazione::getFormazioneBySquadraAndGiornata($request->get('squadra'),$request->get('giornata'));
if($dettaglio == FALSE && $formazione == FALSE)
	Request::send404();

$utente = Utente::getById($request->get('squadra'));
$maxGiornate = Punteggio::getGiornateWithPunt();
for($i = 1;$i <= $maxGiornate;$i++)
	$giornate[$i] = $i;

if($dettaglio != FALSE)
	$titolari = array_splice($dettaglio,0,11);
else
	$titolari = FALSE;

$quickLinks->set('giornata',$giornate,"",array('squadra'=>$request->get('squadra')));

$contentTpl->assign('somma',$utente->getPunteggioByGiornata($request->get('giornata')));
$contentTpl->assign('titolari',$titolari);
$contentTpl->assign('panchinari',$dettaglio);
$contentTpl->assign('penalità',Punteggio::getPenalitàBySquadraAndGiornata($request->get('squadra'),$request->get('giornata')));
$contentTpl->assign('squadraDett',$utente);
$operationTpl->assign('squadre',Utente::getByField('idLega',$_SESSION['legaView']));
$operationTpl->assign('giornate',$giornate);
?>
