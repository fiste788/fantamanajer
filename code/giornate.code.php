<?php 
require_once(INCDIR . 'giornata.inc.php');

$giornataObj = new giornata();
$giornate = $giornataObj->getAllGiornate();
if(isset($_POST['submit']))
{
	if(count($_POST['dataFine']) == count($giornate) && count($_POST['dataInizio']) == count($giornate))
	{
		foreach($_POST['dataInizio'] as $key=>$val)
			if(!empty($val) && $val != $giornate[$key]['dataInizio'])
				$date[$key]['dataInizio'] = $val;
		foreach($_POST['dataFine'] as $key=>$val)
			if(!empty($val) && $val != $giornate[$key]['dataFine'])
				$date[$key]['dataFine'] = $val;
		if(isset($date) && $giornataObj->updateGiornate($date))
		{
			$message[0] = 0;
			$message[1] = "Operazione effettuata con successo";
			$giornate = $giornataObj->getAllGiornate();
		}
		else
		{
			$message[0] = 1;
			$message[1] = "Errore nell'esecuzione. Controlla il formato delle date";
		}
	}
	else
	{
		$message[0] = 1;
		$message[1] = "Non hai compiulato tutti i campi";
	}
}
$contenttpl->assign('giornate',$giornate);
if(isset($message))
	$contenttpl->assign('message',$message);
?>
