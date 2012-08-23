<?php 
require_once(INCDBDIR . 'giocatore.db.inc.php');

$defaultRuolo = $request->has('ruolo') ? $request->get('ruolo') : 'P';
$defaultPartite = $request->has('partite') ? $request->get('partite') : (floor((GIORNATA - 1) / 2) + 1);
$defaultSufficenza = $request->has('sufficenza') ? $request->get('sufficenza') : 6;

$freeplayer = Giocatore::getFreePlayer($defaultRuolo,$_SESSION['legaView']);

$contentTpl->assign('freeplayer',$freeplayer);
$contentTpl->assign('defaultPartite',$defaultPartite);
$contentTpl->assign('defaultSufficenza',$defaultSufficenza);
$operationTpl->assign('validFilter',is_numeric($defaultSufficenza) && is_numeric($defaultPartite));
$operationTpl->assign('ruolo',$defaultRuolo);
$operationTpl->assign('ruoli',$ruoli);
$operationTpl->assign('defaultSufficenza',$defaultSufficenza);
$operationTpl->assign('defaultPartite',$defaultPartite);
?>
