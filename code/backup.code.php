<?php 
require(INCDIR.'backup.inc.php');
require(INCDIR.'fileSystem.inc.php');

$fileSystemObj = new fileSystem();
$path = 'db';
$backupName = $path.'/backup_'. date("Y-m-d H:i:s") . '.gz' ;
$backupObj = new MySQLDump(DBNAME,$backupName,TRUE,FALSE);
//ESEGUO IL BACKUP SETTIMANALE DEL DB
if($backupObj->dodump())
{
	$backup[0] = 0;
	$backup[1] = 'Backup eseguito correttamente';
	$files = $fileSystemObj->getFileIntoFolder($path);
	rsort($files);
	if(count($files) > 8)
	{
		$lastFile = array_pop($files);
		unlink($path.'/'.$lastFile);
	}
}
else
{
	$backup[0] = 1;
	$backup[1] = 'Errore nella creazione del backup';
}
$contenttpl->assign('backup',$backup);
?>
