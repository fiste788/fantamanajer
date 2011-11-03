<?php 
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'formazione.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');

$giornate = Punteggio::getGiornateWithPunt();
$penalità = Punteggio::getPenalitàBySquadraAndGiornata($request->get('squadra'),$request->get('giornata'));

if($penalità != FALSE)
	$contentTpl->assign('penalità',$penalità);
if($request->get('squadra') != NULL && $request->get('giornata') != NULL && $request->get('squadra') > 0 && $giornata > 0 && $request->get('giornata') <= $giornate)
{
	if(Formazione::getFormazioneBySquadraAndGiornata($request->get('squadra'),$request->get('giornata')) != FALSE)
	{
		$formazione = Giocatore::getVotiGiocatoriByGiornataAndSquadra($request->get('giornata'),$request->get('squadra'));
		$titolari = array_splice($formazione,0,11);
		$contentTpl->assign('somma',Punteggio::getPunteggi($request->get('squadra'),$request->get('giornata')));
		$contentTpl->assign('titolari',$titolari);
		$contentTpl->assign('panchinari',$formazione);
	}
	else
	{
		$contentTpl->assign('tirolari',FALSE);
		$contentTpl->assign('panchinari',FALSE);
		$contentTpl->assign('somma',0);
	}
}
else
	$contentTpl->assign('titolari',NULL);

if($request->get('giornata') -1 > 0)
{
	$idPrec = $request->get('giornata') -1;
	$quickLinks->prec->href = Links::getLink('dettaglioGiornata',array('giornata'=>$idPrec,'squadra'=>$request->get('squadra')));
	$quickLinks->prec->title = "Giornata " . $idPrec;
}	
else
{
	$idPrec = FALSE;
	$quickLinks->prec = FALSE;
}
if($request->get('giornata') + 1 <= $giornate)
{
	$idSucc = $request->get('giornata') + 1;
	$quickLinks->succ->href = Links::getLink('dettaglioGiornata',array('giornata'=>$idSucc,'squadra'=>$request->get('squadra')));
	$quickLinks->succ->title = "Giornata " . $idSucc;
}	
else
{
	$idSucc = FALSE;
	$quickLinks->succ = FALSE;
}
	
$contentTpl->assign('squadraDett',Utente::getById($request->get('squadra')));
$operationTpl->assign('squadre',Utente::getByField('idLega',$_SESSION['legaView']));
$operationTpl->assign('penalità',$penalità);
$operationTpl->assign('giornate',$giornate);
$layoutTpl->assign('quickLinks',$quickLinks);
?>
