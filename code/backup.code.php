<?php 
require_once(INCDIR.'backup.inc.php');
require_once(INCDIR.'fileSystem.inc.php');

$fileSystemObj = new fileSystem();
$path = 'db';
$name = 'backup_'. date("Y-m-d_H:i:s") . '.sql' ;
$backupName = $path . '/' . $name;
$backupObj = new MySQLDump(DBNAME,$backupName,FALSE,FALSE);

if($backupObj->dodump())
{
	$handle = fopen('docs/nomeBackup.txt','w');
	fwrite($handle,$name);
	fclose($handle);
	$contenttpl->assign('message','Operazione effettuata correttamente');
	$files = $fileSystemObj->getFileIntoFolder($path);
	rsort($files);
	if(count($files) > 8)
	{
		$lastFile = array_pop($files);
		unlink($path.'/'.$lastFile);
	}
}
else
	$contenttpl->assign('message','Si sono verificati degli errori');
?>
