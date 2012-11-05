<?php
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'lega.db.inc.php');

$filterLega = NULL;
if(isset($_POST['lega']))
	$filterLega = $_POST['lega'];
if($_SESSION['roles'] == '1')
	$filterLega = $_SESSION['idLega'];

$elencoLeghe = Lega::getList();


$contentTpl->assign('elencoLeghe',$elencoLeghe);
$contentTpl->assign('lega',$filterLega);
if($filterLega != NULL && $filterLega != 0)
	$contentTpl->assign('elencoSquadre',Utente::getElencoSquadreByLega($filterLega));
$operationTpl->assign('elencoLeghe',$elencoLeghe);
$operationTpl->assign('lega',$filterLega);
?>
