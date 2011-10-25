<?php
require_once(INCDBDIR . 'club.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(VIEWDIR . 'ClubStatistiche.view.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDIR . 'mail.inc.php');
	
$clubdett = ClubStatistiche::getById($request->get('id'));
$elencoClub = Club::getList();
$keys = array_keys($elencoClub);
$current = array_search($request->get('id'),$keys);
if(isset($keys[($idPrec = $current - 1)]))
{
	$quickLinks->prec->href = Links::getLink('dettaglioClub',array('club'=>$keys[$idPrec]));
	$quickLinks->prec->title = $elencoClub[$keys[$idPrec]]->nomeClub;
}	
else
	$quickLinks->prec = FALSE;

if(isset($keys[($idSucc = $current + 1)]))
{
	$quickLinks->succ->href = Links::getLink('dettaglioClub',array('club'=>$keys[$idSucc]));
	$quickLinks->succ->title = $elencoClub[$keys[$idSucc]]->nomeClub;
}	
else
	$quickLinks->succ = FALSE;

$giocatori = GiocatoreStatistiche::getByField('idClub',$request->get('id'));
$pathClub = CLUBSURL . $filterClub . '.png';

$contentTpl->assign('pathClub',$pathClub);
$contentTpl->assign('giocatori',$giocatori);
$contentTpl->assign('clubDett',$clubdett);


$operationTpl->assign('elencoClub',$elencoClub);

?>
