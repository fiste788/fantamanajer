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
$ruoplu = array('P'=>'Portieri','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');

$dettaglio = $giocatoreObj->getGiocatoreByIdWithStats($filterId,$_SESSION['legaView']);
$pathfoto = 'foto/' . $dettaglio['dettaglio']['idGioc'] . '.jpg';
$pathclub = 'clubs/' . $dettaglio['dettaglio']['idClub'] . '.png';
if(!@file(IMGSURL . $pathfoto))
	$pathfoto = 'foto/nophoto.jpg';

if($_SESSION['logged'] == TRUE)
{
	if(!empty($dettaglio['dettaglio']['idUtente']))		// carico giocatori della squadra
	{
		$squadra = $dettaglio['dettaglio']['idUtente'];
		$elencoGiocatori = $giocatoreObj->getGiocatoriByIdSquadra($squadra);
		$contenttpl->assign('idsquadra',$squadra);
		$dettaglioSquadra= $utenteObj->getSquadraById($squadra);
		$operationtpl->assign('label',$dettaglioSquadra['nome']);
		$contenttpl->assign('label',$dettaglioSquadra['nome']);
	}
	else			// carico giocatori liberi
	{
		$ruolo = $dettaglio['dettaglio']['ruolo'];
		$elencoGiocatori = $giocatoreObj->getFreePlayer($ruolo,$_SESSION['datiLega']['idLega']);
		$operationtpl->assign('label',$ruoplu[$ruolo]." liberi");
		$contenttpl->assign('label',$ruoplu[$ruolo]." liberi");
	}

}
else			// carico giocatori del club
{
	$club = $dettaglio['dettaglio']['nomeClub'];
	$elencoGiocatori = $giocatoreObj->getGiocatoriByIdClub($dettaglio['dettaglio']['idClub']);
	$operationtpl->assign('label',$club);
	$contenttpl->assign('label',$club);
}
$keys = array_keys($elencoGiocatori);

$quickLinks = array();
if(isset($keys[array_search($filterId,$keys)-1]))
{
	$idPrec = $keys[array_search($filterId,$keys)-1];
	$quickLinks['prec']['href'] = $contenttpl->linksObj->getLink('dettaglioGiocatore',array('id'=>$idPrec));
	$quickLinks['prec']['title'] = $elencoGiocatori[$idPrec]['cognome'] . ' ' . $elencoGiocatori[$idPrec]['nome'];
}
else
{
	$idPrec = FALSE;
	$quickLinks['prec'] = FALSE;
}

if(isset($keys[array_search($filterId,$keys)+1]))
{
	$idSucc = $keys[array_search($filterId,$keys)+1];
	$quickLinks['succ']['href'] = $contenttpl->linksObj->getLink('dettaglioGiocatore',array('id'=>$idSucc));
	$quickLinks['succ']['title'] = $elencoGiocatori[$idSucc]['cognome'] . ' ' . $elencoGiocatori[$idSucc]['nome'];
}
else
{
	$idSucc = FALSE;
	$quickLinks['succ'] = FALSE;
}

$contenttpl->assign('dettaglioGioc',$dettaglio);
$contenttpl->assign('pathFoto',IMGSURL . $pathfoto);
$contenttpl->assign('pathClub',IMGSURL . $pathclub);
$contenttpl->assign('ruoli',$ruo);
$contenttpl->assign('ruoliPlurale',$ruoplu);
$operationtpl->assign('idGioc',$filterId);
$operationtpl->assign('elencoGiocatori',$elencoGiocatori);
$operationtpl->assign('giocPrec',$idPrec);
$operationtpl->assign('giocSucc',$idSucc);
$layouttpl->assign('quickLinks',$quickLinks);
?>
