<?php 
require_once(INCDBDIR . 'giocatore.db.inc.php');

$defaultRuolo = Request::getInstance()->has('ruolo') ? Request::getInstance()->get('ruolo') : 'P';
$defaultPartite = Request::getInstance()->has('partite') ? Request::getInstance()->get('partite') : (floor((GIORNATA - 1) / 2) + 1);
$defaultSufficenza = Request::getInstance()->has('sufficenza') ? Request::getInstance()->get('sufficenza') : 6;

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
