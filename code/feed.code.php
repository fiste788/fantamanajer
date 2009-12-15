<?php 
require_once(INCDIR . 'evento.db.inc.php');
$eventoObj = new evento();

$filterEvento = NULL;
if(isset($_POST['evento']))
	$filterEvento = $_POST['evento'];
	
$eventi = $eventoObj->getEventi($_SESSION['legaView'],$filterEvento,0,25);

$contenttpl->assign('eventi',$eventi);
$operationtpl->assign('evento',$filterEvento);
?>
