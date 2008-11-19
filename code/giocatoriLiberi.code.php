<?php 
require_once(INCDIR.'giocatore.inc.php');

$giocatoreObj = new giocatore();

$order = NULL;
$v = NULL;
if(isset($_GET['order']))
	$order = $_GET['order']; 
if(isset($_GET['v']))
	$v = $_GET['v'];
	
if(isset($_POST['order']))
	$order = $_POST['order']; 
if(isset($_POST['v']))
	$v = $_POST['v'];

$contenttpl->assign('getorder',$order);
$contenttpl->assign('getv',$v);

if(!isset($_SESSION['data']['freeplayer']))
{
	$_SESSION['data']['freeplayer']['ruolo'] = 'P';
	$_SESSION['data']['freeplayer']['suff'] = 6;
	$_SESSION['data']['freeplayer']['partite'] = floor(($giornata - 1) / 2) + 1;
}

if(isset($_GET['ruolo']))
	$_SESSION['data']['freeplayer']['ruolo'] = $_GET['ruolo'];
if(isset($_GET['suff']))
	$_SESSION['data']['freeplayer']['suff'] = $_GET['suff'];
if(isset($_GET['partite']))
	$_SESSION['data']['freeplayer']['partite'] = $_GET['partite'];
	
if(isset($_POST['ruolo']))
	$_SESSION['data']['freeplayer']['ruolo'] = $_POST['ruolo'];
if(isset($_POST['suff']))
	$_SESSION['data']['freeplayer']['suff'] = $_POST['suff'];
if(isset($_POST['partite']))
	$_SESSION['data']['freeplayer']['partite'] = $_POST['partite'];
	
$ruolo = $_SESSION['data']['freeplayer']['ruolo'];
$suff = $_SESSION['data']['freeplayer']['suff'];
$partite = $_SESSION['data']['freeplayer']['partite'];

if(is_numeric($suff) && is_numeric($partite))
	$contenttpl->assign('appo',TRUE);
else
	$contenttpl->assign('appo',FALSE);
	
$contenttpl->assign('ruolo',$ruolo);
$contenttpl->assign('suff',$suff);
$contenttpl->assign('partite',$partite);	

$freeplayer = $giocatoreObj->getFreePlayer($ruolo);
foreach($freeplayer as $key => $val)
{
	$freeplayer[$key]['nome'] = $val['nome'];
	$freeplayer[$key]['cognome'] = $val['cognome'];
	$freeplayer[$key]['club'] = $val['nomeClub'];
	$freeplayer[$key]['voti'] = substr($val['mediaPunti'],0,4);
	$freeplayer[$key]['votiAll'] = $val['mediaPunti'];
	$freeplayer[$key]['votiEff'] = substr($val['mediaVoti'],0,4);
	$freeplayer[$key]['votiEffAll'] = $val['mediaVoti'];	
	$freeplayer[$key]['partiteGiocate'] = $val['presenze'];
}
$sort_arr = array();
foreach($freeplayer as $uniqid => $row)
	foreach($row as $key => $value)
		$sort_arr[$key][$uniqid] = $value;

if($order != NULL)
{
	if($v == 'asc')
		array_multisort($sort_arr[$order] , SORT_ASC , $freeplayer);
	elseif($v == 'desc')
		array_multisort($sort_arr[$order] , SORT_DESC , $freeplayer);
}
$orderBy = array();
$orderBy[] = array('campo'=>'cognome','default'=>'asc');
$orderBy[] = array('campo'=>'nome','default'=>'asc');
$orderBy[] = array('campo'=>'club','default'=>'asc');
$orderBy[] = array('campo'=>'voti','default'=>'desc');
$orderBy[] = array('campo'=>'votiEff','default'=>'desc');
$orderBy[] = array('campo'=>'partiteGiocate','default'=>'desc');

foreach($orderBy as $key => $val)
{
	if(!isset($v) || $order != $val['campo'])
		$link[$val['campo']] = $contenttpl->linksObj->getLink('giocatoriLiberi',array('order'=>$val['campo'],'v'=>$val['default'],'ruolo'=>$ruolo,'suff'=>$suff,'partite'=>$partite));
	elseif($order == $val['campo'])
	{
		if($v == 'asc')
			$link[$val['campo']] = $contenttpl->linksObj->getLink('giocatoriLiberi',array('order'=>$val['campo'],'v'=>'desc','ruolo'=>$ruolo,'suff'=>$suff,'partite'=>$partite));
		else
			$link[$val['campo']] = $contenttpl->linksObj->getLink('giocatoriLiberi',array('order'=>$val['campo'],'v'=>'asc','ruolo'=>$ruolo,'suff'=>$suff,'partite'=>$partite));
	}
}

$contenttpl->assign('link',$link);
$contenttpl->assign('freeplayer',$freeplayer);
?>
