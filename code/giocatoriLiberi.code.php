<?php 
require_once(INCDIR . 'giocatore.db.inc.php');

$giocatoreObj = new giocatore();

$filterOrder = NULL;
$filterVersus = NULL;
$ruoli = array('P' => 'Portieri', 'D' => 'Difensori', 'C' => 'Centrocampisti', 'A' =>'Attaccanti');
if(isset($_GET['order']))
	$filterOrder = $_GET['order']; 
if(isset($_GET['v']))
	$filterVersus = $_GET['v'];
	
if(isset($_POST['order']))
	$filterOrder = $_POST['order']; 
if(isset($_POST['v']))
	$filterVersus = $_POST['v'];

if(!isset($_SESSION['userNavigationData']['freeplayer']))
{
	$_SESSION['userNavigationData']['freeplayer']['ruolo'] = 'P';
	$_SESSION['userNavigationData']['freeplayer']['suff'] = 6;
	$_SESSION['userNavigationData']['freeplayer']['partite'] = floor((GIORNATA - 1) / 2) + 1;
}

if(isset($_GET['ruolo']))
	$_SESSION['userNavigationData']['freeplayer']['ruolo'] = $_GET['ruolo'];
if(isset($_GET['suff']))
	$_SESSION['userNavigationData']['freeplayer']['suff'] = $_GET['suff'];
if(isset($_GET['partite']))
	$_SESSION['userNavigationData']['freeplayer']['partite'] = $_GET['partite'];
	
if(isset($_POST['ruolo']))
	$_SESSION['userNavigationData']['freeplayer']['ruolo'] = $_POST['ruolo'];
if(isset($_POST['suff']))
	$_SESSION['userNavigationData']['freeplayer']['suff'] = $_POST['suff'];
if(isset($_POST['partite']))
	$_SESSION['userNavigationData']['freeplayer']['partite'] = $_POST['partite'];
	
$filterRuolo = $_SESSION['userNavigationData']['freeplayer']['ruolo'];
$filterSuff = $_SESSION['userNavigationData']['freeplayer']['suff'];
$filterPartite = $_SESSION['userNavigationData']['freeplayer']['partite'];

if(is_numeric($filterSuff) && is_numeric($filterPartite))
	$contenttpl->assign('validFilter',TRUE);
else
	$contenttpl->assign('validFilter',FALSE);

$freeplayer = $giocatoreObj->getFreePlayer($filterRuolo,$_SESSION['legaView']);
foreach($freeplayer as $key => $val)
{
	if(isset($val->mediaPunti))
		$freeplayer[$key]->voti = $val->mediaPunti;
	else
		$freeplayer[$key]->voti = "";
		
	if(isset($val->mediaVoti))
		$freeplayer[$key]->votiEff = $val->mediaVoti;
	else
		$freeplayer[$key]->votiEff = "";
	
	if(isset($val->presenze))
		$freeplayer[$key]->partiteGiocate = $val->presenze;
	else
		$freeplayer[$key]->partiteGiocate = 0;
}
$sort_arr = array();
foreach($freeplayer as $uniqid => $row)
	foreach($row as $key => $value)
		$sort_arr[$key][$uniqid] = $value;
if($filterOrder != NULL)
{
	if($filterVersus == 'asc')
		array_multisort($sort_arr[$filterOrder] , SORT_ASC , $freeplayer);
	elseif($filterVersus == 'desc')
		array_multisort($sort_arr[$filterOrder] , SORT_DESC , $freeplayer);
}

$orderBy = array();
$orderBy[] = array('campo'=>'cognome','default'=>'asc');
$orderBy[] = array('campo'=>'nome','default'=>'asc');
$orderBy[] = array('campo'=>'nomeClub','default'=>'asc');
$orderBy[] = array('campo'=>'avgPunti','default'=>'desc');
$orderBy[] = array('campo'=>'avgVoti','default'=>'desc');
$orderBy[] = array('campo'=>'presenzeVoto','default'=>'desc');

foreach($orderBy as $key => $val)
{
	if(!isset($v) || $filterOrder != $val['campo'])
		$link[$val['campo']] = $contenttpl->linksObj->getLink('giocatoriLiberi',array('order'=>$val['campo'],'v'=>$val['default'],'ruolo'=>$filterRuolo,'suff'=>$filterSuff,'partite'=>$filterPartite));
	elseif($order == $val['campo'])
	{
		if($filterVersus == 'asc')
			$link[$val['campo']] = $contenttpl->linksObj->getLink('giocatoriLiberi',array('order'=>$val['campo'],'v'=>'desc','ruolo'=>$filterRuolo,'suff'=>$filterSuff,'partite'=>$filterPartite));
		else
			$link[$val['campo']] = $contenttpl->linksObj->getLink('giocatoriLiberi',array('order'=>$val['campo'],'v'=>'asc','ruolo'=>$filterRuolo,'suff'=>$filterSuff,'partite'=>$filterPartite));
	}
}

$contenttpl->assign('order',$filterOrder);
$contenttpl->assign('v',$filterVersus);
$contenttpl->assign('link',$link);
$contenttpl->assign('freeplayer',$freeplayer);
$contenttpl->assign('suff',$filterSuff);
$contenttpl->assign('partite',$filterPartite);
$operationtpl->assign('ruolo',$filterRuolo);
$operationtpl->assign('suff',$filterSuff);
$operationtpl->assign('partite',$filterPartite);
$operationtpl->assign('ruoli',$ruoli);
?>
