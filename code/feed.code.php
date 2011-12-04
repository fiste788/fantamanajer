<?php 
require_once(INCDBDIR . 'evento.db.inc.php');

$eventi = Evento::getEventi($_SESSION['legaView'],$request->get('evento'),0,25);

$contentTpl->assign('eventi',$eventi);
?>
