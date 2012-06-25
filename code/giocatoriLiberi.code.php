<?php 
require_once(INCDBDIR . 'giocatore.db.inc.php');

$freeplayer = Giocatore::getFreePlayer($request->get('ruolo'),$_SESSION['legaView']);
$defaultPartite = $request->has('partite') ? $request->get('partite') : (floor((GIORNATA - 1) / 2) + 1);
$defaultSufficenza = $request->has('sufficenza') ? $request->get('sufficenza') : 6;

$contentTpl->assign('freeplayer',$freeplayer);
$contentTpl->assign('defaultPartite',$defaultPartite);
$contentTpl->assign('defaultSufficenza',$defaultSufficenza);
$operationTpl->assign('validFilter',is_numeric($defaultSufficenza) && is_numeric($defaultPartite));
$operationTpl->assign('ruolo',$request->get('ruolo'));
$operationTpl->assign('ruoli',$ruoli);
$operationTpl->assign('defaultSufficenza',$defaultSufficenza);
$operationTpl->assign('defaultPartite',$defaultPartite);
?>
