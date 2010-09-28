<?php 
require_once(INCDIR . "lega.db.inc.php");

if(isset($_POST['nomeLega']))
{
	$flag = 0;
	foreach($_POST as $key=>$val)
		if($key != "capitano" && $key != "jolly" && $key != "premi" && empty($val))
			$flag = 1;
	if(!is_numeric($_POST['numTrasferimenti']) || !is_numeric($_POST['numSelezioni']) || !is_numeric($_POST['minFormazione']) )
		$flag = 2;
	if($flag == 0)
	{
		$_POST['nomeLega'] = trim(addslashes(stripslashes($_POST['nomeLega'])));
		$_POST['premi'] = trim(addslashes(stripslashes($_POST['premi'])));
		if(Lega::updateImpostazioni($_POST))
		{
			$_SESSION['datiLega'] = Lega::getLegaById($_SESSION['idLega']);
			$message->success("Operazione effettuata correttamente");
		}
		else
			$message->error("Errore nell'esecuzione");
	}
	elseif($flag == 1)
		$message->error("Non hai compilato tutti i campi");
	else
		$message->error("Tipo di dati incorretto. Controlla i valori numerici");
}
$default = Lega::getDefaultValue();
$contentTpl->assign('default',$default);
?>
