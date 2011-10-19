<?php 
require_once(INCDBDIR . "lega.db.inc.php");

if(isset($_POST['nome']))
{
    $lega = Lega::getById($_SESSION['idLega']);
	$flag = 0;
	foreach($_POST as $key=>$val)
		if($key != "capitano" && $key != "jolly" && $key != "premi" && empty($val))
			$flag = 1;
	if(!is_numeric($_POST['numTrasferimenti']) || !is_numeric($_POST['numSelezioni']) || !is_numeric($_POST['minFormazione']) )
		$flag = 2;
	if($flag == 0)
	{
	    if($lega == NULL)
	        $lega = new Lega();
		$lega->setNome(trim(addslashes(stripslashes($_POST['nome']))));
		$lega->setPremi(trim(addslashes(stripslashes($_POST['premi']))));
		$lega->setCapitano($_POST['capitano']);
		$lega->setNumTrasferimenti($_POST['numTrasferimenti']);
		$lega->setNumSelezioni($_POST['numSelezioni']);
		$lega->setMinFormazione($_POST['minFormazione']);
		$lega->setPunteggioFormazioneDimenticata($_POST['punteggioFormazioneDimenticata']);
		$lega->setJolly($_POST['jolly']);
		if($lega->save())
		{
			$_SESSION['datiLega'] = Lega::getById($_SESSION['idLega']);
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
$contentTpl->assign('default',Lega::getDefaultValue());
?>
