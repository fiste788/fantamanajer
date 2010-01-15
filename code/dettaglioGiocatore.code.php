<?php 
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'utente.db.inc.php');

$giocatoreObj = new giocatore();
$utenteObj = new utente();

if(isset($_GET['id']))
	$filterId = $_GET['id'];
if(isset($_GET['edit']))
	$filterEdit = $_GET['edit'];
if(isset($_POST['id']))
	$filterId = $_POST['id'];
if(isset($_POST['edit']))
	$filterEdit = $_POST['edit'];

$ruo = array('P'=>'Portiere','D'=>'Difensore','C'=>'Centrocampista','A'=>'Attaccante');
$ruoPlu = array('P'=>'Portieri','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');

$dettaglio = $giocatoreObj->getGiocatoreByIdWithStats($filterId,$_SESSION['legaView']);
$pathFoto = PLAYERSDIR . $dettaglio['dettaglio']->idGioc . '.jpg';
$pathClub = CLUBSURL . $dettaglio['dettaglio']->idClub . '.png';
if(!file_exists($pathFoto))
	$pathFoto = IMGSURL . 'no-photo.png';
else
	$pathFoto = PLAYERSURL . $dettaglio['dettaglio']->idGioc . '.jpg';

if($_SESSION['logged'] == TRUE)
{
	if(!empty($dettaglio['dettaglio']->idUtente))		// carico giocatori della squadra
	{
		$squadra = $dettaglio['dettaglio']->idUtente;
		$elencoGiocatori = $giocatoreObj->getGiocatoriByIdSquadra($squadra);
		$contentTpl->assign('idsquadra',$squadra);
		$dettaglioSquadra= $utenteObj->getSquadraById($squadra);
		$operationTpl->assign('label',$dettaglioSquadra->nome);
		$contentTpl->assign('label',$dettaglioSquadra->nome);
	}
	else			// carico giocatori liberi
	{
		$ruolo = $dettaglio['dettaglio']->ruolo;
		$elencoGiocatori = $giocatoreObj->getFreePlayer($ruolo,$_SESSION['datiLega']->idLega);
		$operationTpl->assign('label',$ruoPlu[$ruolo] . " liberi");
		$contentTpl->assign('label',$ruoPlu[$ruolo] . " liberi");
	}

}
else			// carico giocatori del club
{
	$club = $dettaglio['dettaglio']->nomeClub;
	$elencoGiocatori = $giocatoreObj->getGiocatoriByIdClub($dettaglio['dettaglio']->idClub);
	$operationTpl->assign('label',$club);
	$contentTpl->assign('label',$club);
}
$keys = array_keys($elencoGiocatori);

if(isset($keys[array_search($filterId,$keys) - 1]))
{
	$idPrec = $keys[array_search($filterId,$keys) - 1];
	$quickLinks->prec->href = $contentTpl->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$idPrec));
	$quickLinks->prec->title = $elencoGiocatori[$idPrec]->cognome . ' ' . $elencoGiocatori[$idPrec]->nome;
}
else
{
	$idPrec = FALSE;
	$quickLinks->prec = FALSE;
}
if(isset($keys[array_search($filterId,$keys) + 1]))
{
	$idSucc = $keys[array_search($filterId,$keys) + 1];
	$quickLinks->succ->href = $contentTpl->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$idSucc));
	$quickLinks->succ->title = $elencoGiocatori[$idSucc]->cognome . ' ' . $elencoGiocatori[$idSucc]->nome;
}
else
{
	$idSucc = FALSE;
	$quickLinks->succ = FALSE;
}

$contentTpl->assign('dettaglioGioc',$dettaglio);
$contentTpl->assign('pathFoto',$pathFoto);
$contentTpl->assign('pathClub',$pathClub);
$contentTpl->assign('ruoli',$ruo);
$contentTpl->assign('ruoliPlurale',$ruoPlu);
$operationTpl->assign('idGioc',$filterId);
$operationTpl->assign('elencoGiocatori',$elencoGiocatori);
