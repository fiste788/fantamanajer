<?php 
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'utente.inc.php');
$ruoplu = array('P'=>'Portieri','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');

$giocatoreObj = new giocatore();
$utenteObj = new utente();

if(isset($_GET['id']))
	$id = $_GET['id'];
if(isset($_GET['edit']))
	$edit = $_GET['edit'];
if(isset($_POST['id']))
	$id = $_POST['id'];
if(isset($_POST['edit']))
	$edit = $_POST['edit'];

$contenttpl->assign('idgioc',$id);
$dettaglio = $giocatoreObj->getGiocatoreByIdWithStats($id);

$pathfoto = 'foto/' . $dettaglio[0]['idGioc'] . '.jpg';
if(!file_exists(IMGDIR . $pathfoto))
	$pathfoto = 'foto/nophoto.jpg';



$contenttpl->assign('dettaglioGioc',$dettaglio);
$contenttpl->assign('pathfoto',IMGSURL . $pathfoto);
if($_SESSION['logged'] == TRUE)
{
	if(!empty($dettaglio[0]['idUtente']))		// carico giocatori della squadra
	{
		$squadra = $dettaglio[0]['idUtente'];
		$elencogiocatori = $giocatoreObj->getGiocatoriByIdSquadra($squadra);
		$contenttpl->assign('idsquadra',$squadra);
		$dettagliosquadra= $utenteObj->getSquadraById($squadra);
		$contenttpl->assign('label',$dettagliosquadra['nome']);
	}
	else			// carico giocatori liberi
	{
		$ruolo = $dettaglio[0]['ruolo'];
		$elencogiocatori = $giocatoreObj->getFreePlayer($ruolo,$_SESSION['datiLega']['idLega']);
		$contenttpl->assign('label',$ruoplu[$ruolo]." liberi");
	}

}
else			// carico giocatori del club
{
	$club = $dettaglio[0]['nomeClub'];
	$elencogiocatori = $giocatoreObj->getGiocatoriByIdClub($dettaglio[0]['idClub']);
	$contenttpl->assign('label',$club);
}

	$contenttpl->assign('elencogiocatori',$elencogiocatori);
	$keys=array_keys($elencogiocatori);

	if(isset($keys[array_search($id,$keys)-1]))
		$contenttpl->assign('giocprec',$keys[array_search($id,$keys)-1]);
	else
		$contenttpl->assign('giocprec',false);

	if(isset($keys[array_search($id,$keys)+1]))
		$contenttpl->assign('giocsucc',$keys[array_search($id,$keys)+1]);
	else
		$contenttpl->assign('giocsucc',false);

?>
