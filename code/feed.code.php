<?php 
require_once(INCDIR . 'evento.db.inc.php');
$eventoObj = new evento();

$filterEvento = NULL;
if(isset($_POST['evento']))
	$filterEvento = $_POST['evento'];
	
$eventi = $eventoObj->getEventi($_SESSION['legaView'],$filterEvento,0,25);

$contentTpl->assign('eventi',$eventi);
$operationTpl->assign('evento',$filterEvento);
?>
