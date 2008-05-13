<?php 
require(INCDIR.'giocatore.inc.php');

$order = NULL;
$v = NULL;
if(isset($_GET['order']))
	$order = $_GET['order']; 
if(isset($_GET['v']))
	$v = $_GET['v'];

$contenttpl->assign('getorder',$order);
$contenttpl->assign('getv',$v);
$ruolo = 'P';
$suff = 6;
$partite = floor(($giornata - 1) / 2) + 1;
if(isset($_GET['ruolo']))
	$ruolo = $_GET['ruolo'];
if(isset($_GET['suff']))
	$suff = $_GET['suff'];
if(isset($_GET['partite']))
	$partite = $_GET['partite'];

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

$giocatoreObj = new giocatore();
$freeplayer = $giocatoreObj->getFreePlayer($ruolo);
foreach($freeplayer as $key=>$val)
{
	$voti = $val['Voti'];
	$voti = explode(';',$voti);
	//echo "<pre>".print_r($voti),"</pre>";
	$mediavoti = array_sum($voti);
	$partitegiocate = count(array_filter($voti,"filter"));
	if($partitegiocate != 0)
		$mediavoti /= $partitegiocate;
	$freeplayer[$key]['Nome'] = $val['Nome'];
	$freeplayer[$key]['Cognome'] = $val['Cognome'];
	$freeplayer[$key]['Voti'] = substr($mediavoti,0,4);
	$freeplayer[$key]['VotiAll'] = $mediavoti;
	$freeplayer[$key]['PartiteGiocate'] = $partitegiocate;
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
$orderBy[] = array('PartiteGiocate','desc');
foreach($orderBy as $key=>$val)
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
}
$contenttpl->assign('link',$link);
$contenttpl->assign('freeplayer',$freeplayer);
?>
