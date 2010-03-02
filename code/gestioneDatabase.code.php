<?php 
require_once(INCDIR . 'fileSystem.inc.php');

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
		$message->success('Query eseguita con successo');
	}
	else
	{
		$dbObj->rollback();
		$message->error('Query non valida: ' . $err);
		$contentTpl->assign('sql',$_POST['query']);
	}
}
if(isset($_GET['action']))
{
	if($_GET['action'] == 'optimize')
	{
		if(dbObj::dbOptimize())
			$message->success('Database ottimizzato con successo');
	}	
	if($_GET['action'] == 'sincronize')
	{
		$sql = fileSystem::getLastBackup();
		echo "b" . $sql;
		if(!$sql)
			$message->warning('Errore nel recupero dell\'ultimo backup');
		else
		{
			$querys = explode(";\n",$sql);
			$dbObj->startTransaction();
			array_pop($querys);
			$err = "";
			foreach($querys as $key=>$val)
				if(!empty($val))
					mysql_query($val) or $err .= MYSQL_ERRNO() . " - " . MYSQL_ERROR() . " " . $val . "\n\n";
			if(empty($err))
			{
				$dbObj->commit();
				$message->success('Sincronizzazione eseguita con successo');
			}
			else
			{
				echo $err;
				$dbObj->rollback();
				$message->error('Errore nella sincronizzazione: ' . $err);
				$contentTpl->assign('sql',$sql);
			}
		}
	}
}
?>
