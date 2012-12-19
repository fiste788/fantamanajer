<?php
require_once(INCDBDIR . "lega.db.inc.php");

if(Request::getInstance()->has('new') && Request::getInstance()->get('new') == "1")
	$lega = new Lega();
else
	$lega = $_SESSION['datiLega'];

$lega->save();
if($lega->getId() == $_SESSION['idLega'])
	$_SESSION['datiLega'] = $lega;
$message->success("Operazione effettuata correttamente");

$contentTpl->assign('lega',$lega)
?>
