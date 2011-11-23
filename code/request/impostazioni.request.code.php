<?php 
require_once(INCDBDIR . "lega.db.inc.php");

if($request->has('new') && $request->get('new') == "1")
	$lega = new Lega();
else
	$lega = $_SESSION['datiLega'];

if($lega->validate()) {
	if($lega->save())
	{
		if($lega->getId() == $_SESSION['idLega'])
			$_SESSION['datiLega'] = $lega;
		$message->success("Operazione effettuata correttamente");
	}
	else
		$message->error("Errore nell'esecuzione");
}

$contentTpl->assign('lega',$lega)
?>
