<?php 
require_once(INCDIR . "leghe.inc.php");

$legheObj = new leghe();
$default = $legheObj->getDefaultValue();

if(isset($_POST['nomeLega']))
{
	$flag = 0;
	foreach($_POST as $key=>$val)
		if($key != "capitano" && empty($val))
			$flag = 1;
	if(!is_numeric($_POST['numTrasferimenti']) || !is_numeric($_POST['numSelezioni']) || !is_numeric($_POST['minFormazione']) )
		$flag = 2;
	if($flag == 0)
	{
		$_POST['nomeLega'] = trim(stripslashes(addslashes($_POST['nomeLega'])));
		if($legheObj->updateImpostazioni($_POST))
		{
			$_SESSION['datiLega'] = $legheObj->getLegaById($_SESSION['idLega']);
			$message[0] = 0;
			$message[1] = "Operazione effettuata correttamente";
		}
		else
		{
			$message[0] = 1;
			$message[1] = "Errore nell'esecuzione";
		}
	}
	elseif($flag == 1)
	{
		$message[0] = 1;
		$message[1] = "Non hai compilato tutti i campi";
	}
	else
	{
		$message[0] = 1;
		$message[1] = "Tipo di dati incorretto. Controlla i valori numerici";
	}
	$contenttpl->assign('messaggio',$message);
}
$contenttpl->assign('default',$default);
?>
