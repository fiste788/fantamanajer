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

function filter($var)
{
	if($var != '-')
		return $var;
}	

$freeplayer = $giocatoreObj->getFreePlayer($ruolo);
foreach($freeplayer as $key=>$val)
{
/*	$voti = $val['Voti'];
	$voti = explode(';',$voti);
	//echo "<pre>".print_r($voti),"</pre>";
	$mediavoti = array_sum($voti);
	$partitegiocate = count(array_filter($voti,"filter"));
	if($partitegiocate != 0)
		$mediavoti /= $partitegiocate;*/
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
    foreach($freeplayer AS $uniqid => $row){
        foreach($row AS $key => $value){
            $sort_arr[$key][$uniqid] = $value;
        }
    }
if($order != NULL)
{
	if($v == 'asc')
		array_multisort($sort_arr[$order] , SORT_ASC , $freeplayer);
	elseif($v == 'desc')
		array_multisort($sort_arr[$order] , SORT_DESC , $freeplayer);
}
$orderBy = array();
$orderBy[] = array('Cognome','asc');
$orderBy[] = array('Nome','asc');
$orderBy[] = array('Club','asc');
$orderBy[] = array('Voti','desc');
$orderBy[] = array('VotiEff','desc');
$orderBy[] = array('PartiteGiocate','desc');
/*foreach($orderBy as $key=>$val)
{
	if(!isset($v) || $order != $val[0])
		$link[$val[0]] = 'index.php?p=freeplayer&amp;order=' . $val[0] . '&amp;v=' . $val[1];
	elseif($order == $val[0])
	{
		if($v == 'asc')
			$link[$val[0]] = 'index.php?p=freeplayer&amp;order=' . $val[0] . '&amp;v=desc';
		else
			$link[$val[0]] = 'index.php?p=freeplayer&amp;order=' . $val[0] . '&amp;v=asc';
	}
	if(isset($ruolo))
		$link[$val[0]] .= '&amp;ruolo=' . $ruolo;
	if(isset($suff))
		$link[$val[0]] .=  '&amp;suff=' . $suff;
	if(isset($partite))
		$link[$val[0]] .=  '&amp;partite=' . $partite;
}*/
foreach($orderBy as $key=>$val)
{
	if(!isset($v) || $order != $val[0])
		$link[$val[0]] = $contenttpl->linksObj->getLink('giocatoriLiberi',array('order'=>$val[0],'v'=>$val[1],'ruolo'=>$ruolo,'suff'=>$suff,'partite'=>$partite));
	elseif($order == $val[0])
	{
		if($v == 'asc')
			$link[$val[0]] = $contenttpl->linksObj->getLink('giocatoriLiberi',array('order'=>$val[0],'v'=>'desc','ruolo'=>$ruolo,'suff'=>$suff,'partite'=>$partite));
		else
			$link[$val[0]] = $contenttpl->linksObj->getLink('giocatoriLiberi',array('order'=>$val[0],'v'=>'asc','ruolo'=>$ruolo,'suff'=>$suff,'partite'=>$partite));
	}
}

$contenttpl->assign('link',$link);
$contenttpl->assign('freeplayer',$freeplayer);
?>
