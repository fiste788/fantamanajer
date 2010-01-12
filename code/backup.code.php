<?php 
require_once(INCDIR . 'backup.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');

$fileSystemObj = new fileSystem();
$name = "backup_" . date("Ymd-His");
$backupName = DBDIR . $name;
$backupGzipObj = new MySQLDump(DBNAME,$backupName . '.gz',TRUE,FALSE);
$backupObj = new MySQLDump(DBNAME,$backupName . '.sql',FALSE,FALSE);
if($backupObj->dodump())
{
	if($backupGzipObj->dodump())
	{
		$handle = fopen(DOCSDIR . 'nomeBackup.txt','r');
		$fileOld = fgets($handle);
		unlink(DBDIR . $fileOld);
		fclose($handle);
		$handle = fopen(DOCSDIR . 'nomeBackup.txt','w');
		fwrite($handle,$name . '.gz');
		fclose($handle);
		$files = $fileSystemObj->getFileIntoFolder(DBDIR);
		rsort($files);
		if(count($files) > 9)
		{
			$lastFile = array_pop($files);
			unlink(DBDIR . $lastFile);
		}
		$message->success("Operazione effettuata correttamente");
	}
	else
		$message->error("Si sono verificati degli errori nel backup compresso");	
}
else
	$message->error("Si sono verificati degli errori");	
$contentTpl->assign('message',$message);
?>
