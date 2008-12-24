<?php 
require_once(INCDIR.'db.inc.php');
require_once(INCDIR.'fileSystem.inc.php');

$dbObj = new db();
$fileSystemObj = new fileSystem();
$result = NULL;
if(isset($_POST['query']) && !empty($_POST['query']))
{
	$querys = explode(';',$_POST['query']);
	$dbObj->startTransaction();
	foreach($querys as $key=>$val)
		if(!empty($val))
			mysql_query($val) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR();
	if(!isset($err))
	{
		$dbObj->commit();
		$messaggio[0] = 0;
		$messaggio[1] = 'Query eseguita con successo';
	}
	else
	{
		$dbObj->rollback();
		$messaggio[0] = 1;
		$messaggio[1] = 'Query non valida: ' . $err;
		$contenttpl->assign('sql',$_POST['query']);
	}
}
if(isset($_GET['action']))
{
	if($_GET['action'] == 'optimize')
	{
		if($dbObj->DbOptimize())
		{
			$messaggio[0] = 0;
			$messaggio[1] = 'Database ottimizzato con successo';
		}
	}	
	if($_GET['action'] == 'sincronize')
	{
		$sql = $fileSystemObj->getLastBackup();
		if(!$sql)
		{
			$messaggio[0] = 1;
			$messaggio[1] = 'Errore nel recupero dell\'ultimo backup';
		}
		else
		{
			//TO-DO: cekko - invece che esplodere così creare un espressione regolare che splitti per ogni ; che però non sia incluso tra apici singoli
			$querys = explode(";\n",$sql);
			echo "<pre>" . print_r($querys,1) . "</pre>";
			$dbObj->startTransaction();
			array_pop($querys);
			foreach($querys as $key=>$val)
				mysql_query($val) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "          ".$val;
			if(!isset($err))
			{
				$dbObj->commit();
				$messaggio[0] = 0;
				$messaggio[1] = 'Sincronizzazione eseguita con successo';
			}
			else
			{
				$dbObj->rollback();
				$messaggio[0] = 1;
				$messaggio[1] = 'Errore nella sincronizzazione: ' . $err;
				$contenttpl->assign('sql',$sql);
			}
		}
	}
}
if(isset($messaggio))
	$contenttpl->assign('messaggio',$messaggio);
?>
