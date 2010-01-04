<?php 
require_once(INCDIR . 'backup.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');

$fileSystemObj = new fileSystem();
$path = DBDIR;
$name = "backup_" . date("Ymd-His");
$backupName = $path . '/' . $name;
$backupGzipObj = new MySQLDump(DBNAME,$backupName . '.gz',TRUE,FALSE);
$backupObj = new MySQLDump(DBNAME,$backupName . '.sql',FALSE,FALSE);
//ob_start();
if($backupObj->dodump())
{
	if($backupGzipObj->dodump())
	{
		$handle = fopen('docs/nomeBackup.txt','r');
		$fileOld = fgets($handle);
		unlink($path . '/' . $fileOld);
		fclose($handle);
		$handle = fopen('docs/nomeBackup.txt','w');
		fwrite($handle,$name . '.gz');
		fclose($handle);
		$files = $fileSystemObj->getFileIntoFolder($path);
		rsort($files);
		if(count($files) > 9)
		{
			$lastFile = array_pop($files);
			unlink($path . '/' . $lastFile);
		}
		$message->success("Operazione effettuata correttamente");
	}
	else
		$message->error("Si sono verificati degli errori nel backup compresso");	
}
else
	$message->error("Si sono verificati degli errori");	
//ob_end_clean();
$contentTpl->assign('message',$message);
?>
