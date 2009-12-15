<?php 
require_once(INCDIR . 'giornata.db.inc.php');

$giornataObj = new giornata();
$giornate = $giornataObj->getAllGiornate();
if(isset($_POST['submit']))
{
	if(count($_POST['dataFine']) == count($giornate) && count($_POST['dataInizio']) == count($giornate))
	{
		foreach($_POST['dataInizio'] as $key=>$val)
			if(!empty($val) && $val != $giornate[$key]->dataInizio)
				$date[$key]['dataInizio'] = $val;
		foreach($_POST['dataFine'] as $key=>$val)
			if(!empty($val) && $val != $giornate[$key]->dataFine)
				$date[$key]['dataFine'] = $val;
		if(isset($date) && $giornataObj->updateGiornate($date))
		{
			$message->success("Operazione effettuata con successo");
			$giornate = $giornataObj->getAllGiornate();
		}
		else
			$message->error("Errore nell'esecuzione. Controlla il formato delle date");
	}
	else
		$message->error("Non hai compiulato tutti i campi");
}
$contenttpl->assign('giornate',$giornate);
?>
