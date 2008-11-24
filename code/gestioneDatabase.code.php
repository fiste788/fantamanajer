<?php 
require_once(INCDIR.'db.inc.php');
require_once(INCDIR.'filesystem.inc.php');

$dbObj = new db();
$fileSystemObj = new fileSystem();
$result = NULL;
if(isset($_POST['query']) && !empty($_POST['query']))
{
	if(mysql_query($_POST['query']))
	{
		$messaggio[0] = 0;
		$messaggio[1] = 'Query eseguita con successo';
	}
	else
	{
		$messaggio[0] = 1;
		$messaggio[1] = 'Query non valida';
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
		$contenttpl->assign('sql',$sql);
	}
}
if(isset($messaggio))
	$contenttpl->assign('messaggio',$messaggio);
?>
