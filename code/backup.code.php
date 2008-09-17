<?php 
require_once(INCDIR.'backup.inc.php');
require_once(INCDIR.'fileSystem.inc.php');

$fileSystemObj = new fileSystem();
$path = 'db';
$backupName = $path.'/backup_'. date("Y-m-d_H:i:s") . '.sql' ;
$backupObj = new MySQLDump(DBNAME,$backupName,FALSE,FALSE);

if(isset($_GET['user']) && trim($_GET['user']) == 'admin' && isset($_GET['pass']) && trim($_GET['pass']) == md5('omordotuanuoraoarounautodromo'))
{
	//ESEGUO IL BACKUP SETTIMANALE DEL DB
	if($backupObj->dodump())
	{
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
}
else
	$contenttpl->assign('message','Non sei autorizzato a eseguire l\'operazione');
?>
