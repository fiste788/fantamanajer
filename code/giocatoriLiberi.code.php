<?php 
require_once(INCDBDIR . 'giocatore.db.inc.php');

$freeplayer = Giocatore::getFreePlayer($request->get('ruolo'),$_SESSION['legaView']);

$contentTpl->assign('freeplayer',$freeplayer);
$operationTpl->assign('validFilter',is_numeric($request->get('sufficienza')) && is_numeric($request->get('partite')));
$operationTpl->assign('ruolo',$request->get('ruolo'));
$operationTpl->assign('ruoli',$ruoli);
?>
