<?php 
require_once(INCDIR.'backup.inc.php');
require_once(INCDIR.'fileSystem.inc.php');

$fileSystemObj = new fileSystem();
$path = 'db';
$name = 'backup_'. date("Y-m-d_H:i:s") . '.sql' ;
$backupName = $path . '/' . $name;
$backupObj = new MySQLDump(DBNAME,$backupName,FALSE,FALSE);
ob_start();
if($backupObj->dodump())
{
	$handle = fopen('docs/nomeBackup.txt','w');
	fwrite($handle,$name);
	fclose($handle);
	$files = $fileSystemObj->getFileIntoFolder($path);
	rsort($files);
	if(count($files) > 8)
	{
		$lastFile = array_pop($files);
		unlink($path.'/'.$lastFile);
	}
	$message[0] = 0;
	$message[1] = "Operazione effettuata correttamente";
}
else
{
	$message[0] = 1;
	$message[1] = "Si sono verificati degli errori";	
}
ob_end_clean();
$contenttpl->assign('message',$message);
?>
