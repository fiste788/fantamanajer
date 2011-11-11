<?php 
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDBDIR . 'club.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');

if(isset($_GET['id']))
	$filterId = $_GET['id'];
if(isset($_GET['edit']))
	$filterEdit = $_GET['edit'];
if(isset($_POST['id']))
	$filterId = $_POST['id'];
if(isset($_POST['edit']))
	$filterEdit = $_POST['edit'];

//$dettaglio = GiocatoreStatistiche::getById($filterId);
if($dettaglio = Giocatore::getGiocatoreByIdWithStats($request->get('id'),$_SESSION['legaView']) === FALSE)
	Request::send404();

$pathFoto = PLAYERSDIR . $dettaglio['dettaglio']->id . '.jpg';
$pathClub = CLUBSURL . $dettaglio['dettaglio']->idClub . '.png';
if(!file_exists($pathFoto))
	$pathFoto = IMGSURL . 'no-photo.png';
else
	$pathFoto = PLAYERSURL . $dettaglio['dettaglio']->id . '.jpg';

if($_SESSION['logged'] == TRUE)
{
	if(!empty($dettaglio['dettaglio']->idUtente))		// carico giocatori della squadra
	{
		$squadra = $dettaglio['dettaglio']->idUtente;
		$elencoGiocatori = GiocatoreStatistiche::getByField('idUtente',$squadra);
		$contentTpl->assign('idUtente',$squadra);
		$dettaglioSquadra= Utente::getById($squadra);
		$operationTpl->assign('label',$dettaglioSquadra->nome);
		$contentTpl->assign('label',$dettaglioSquadra->nome);
	}
	else			// carico giocatori liberi
	{
		$ruolo = $dettaglio['dettaglio']->ruolo;
		$elencoGiocatori = Giocatore::getFreePlayer($ruolo,$_SESSION['datiLega']->idLega);
		$operationTpl->assign('label',$ruoli[$ruolo]->plurale . " liberi");
		$contentTpl->assign('label',$ruoli[$ruolo]->plurale . " liberi");
	}

}
else			// carico giocatori del club
{
	$club = $dettaglio['dettaglio']->nomeClub;
	$elencoGiocatori = Giocatore::getByField('idClub',$dettaglio['dettaglio']->idClub);
	$operationTpl->assign('label',$club);
	$contentTpl->assign('label',$club);
}
$quickLinks->set('id',$elencoGiocatori,"");
/*$keys = array_keys($elencoGiocatori);

if(isset($keys[array_search($filterId,$keys) - 1]))
{
	$idPrec = $keys[array_search($filterId,$keys) - 1];
	$quickLinks->prec->href = Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$idPrec));
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
	$quickLinks->succ->href = Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$idSucc));
	$quickLinks->succ->title = $elencoGiocatori[$idSucc]->cognome . ' ' . $elencoGiocatori[$idSucc]->nome;
}
else
{
	$idSucc = FALSE;
	$quickLinks->succ = FALSE;
}*/
$contentTpl->assign('dettaglioGioc',$dettaglio);
$contentTpl->assign('pathFoto',$pathFoto);
$contentTpl->assign('pathClub',$pathClub);
$operationTpl->assign('idGioc',$filterId);
$operationTpl->assign('elencoGiocatori',$elencoGiocatori);
