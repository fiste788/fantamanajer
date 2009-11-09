<?php 
require_once(INCDIR . "lega.db.inc.php");

$legaObj = new lega();

if(isset($_POST['nomeLega']))
{
	$flag = 0;
	foreach($_POST as $key=>$val)
		if(($key != "capitano" || $key != "premi") && empty($val))
			$flag = 1;
	if(!is_numeric($_POST['numTrasferimenti']) || !is_numeric($_POST['numSelezioni']) || !is_numeric($_POST['minFormazione']) )
		$flag = 2;
	if($flag == 0)
	{
		$_POST['nomeLega'] = trim(addslashes(stripslashes($_POST['nomeLega'])));
		$_POST['premi'] = trim(addslashes(stripslashes($_POST['premi'])));
		if($legaObj->updateImpostazioni($_POST))
		{
			$_SESSION['datiLega'] = $legaObj->getLegaById($_SESSION['idLega']);
			$message['level'] = 0;
			$message['text'] = "Operazione effettuata correttamente";
		}
		else
		{
			$message['level'] = 1;
			$message['text'] = "Errore nell'esecuzione";
		}
	}
	elseif($flag == 1)
	{
		$message['level'] = 1;
		$message['text'] = "Non hai compilato tutti i campi";
	}
	else
	{
		$message['level'] = 1;
		$message['text'] = "Tipo di dati incorretto. Controlla i valori numerici";
	}
	$layouttpl->assign('message',$message);
}
$default = $legaObj->getDefaultValue();
$contenttpl->assign('default',$default);
?>
