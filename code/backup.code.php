<?php 
require_once(INCDIR . 'backup.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');

$logger->start("BACKUP");
$name = date("YmdHis");
$backupName = DBDIR . $name;
$backupObj = new MySQLDump(DBNAME,$backupName . '.sql',FALSE,FALSE);
$logger->info("Creating " . $backupName . ".sql");
if($backupObj->dodump())
{
	$logger->info("Backup " . $backupName . ".sql created succesfully");
	$logger->info("Creating " . $backupName . ".gz");
	file_put_contents($backupName . '.sql.gz',gzencode(file_get_contents($backupName . '.sql'),9));
	$logger->info("Backup " . $backupName . ".sql.gz created succesfully");

	$handle = fopen(DOCSDIR . 'nomeBackup.txt','r');
	$fileOld = DBDIR . fgets($handle) . '.sql';
	if(file_exists($fileOld))
	{
		unlink($fileOld);
		$logger->info("Deleting " . $fileOld );
	}
	fclose($handle);
	$handle = fopen(DOCSDIR . 'nomeBackup.txt','w');
	fwrite($handle,$name);
	fclose($handle);
	$files = fileSystem::getFileIntoFolder(DBDIR);
	rsort($files);
	if(count($files) > 9)
	{
		$lastFile = array_pop($files);
		unlink(DBDIR . $lastFile);
		$logger->info("Deleting " . DBDIR . $lastFile);
	}
	$message->success("Operazione effettuata correttamente");
}
else
	$message->error("Si sono verificati degli errori");	
$logger->end("BACKUP");
$contentTpl->assign('message',$message);
?>
