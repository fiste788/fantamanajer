<?php 
require_once(INCDIR . 'evento.db.inc.php');

$filterEvento = NULL;
if(isset($_POST['evento']))
	$filterEvento = $_POST['evento'];

$eventi = Evento::getEventi($_SESSION['legaView'],$filterEvento,0,25);

$contentTpl->assign('eventi',$eventi);
$operationTpl->assign('evento',$filterEvento);
?>
