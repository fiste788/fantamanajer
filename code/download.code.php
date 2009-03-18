<?php 
require_once(INCDIR.'fileSystem.inc.php');
include_once(INCDIR."createZip.inc.php");
define ("TOZIP","all");
	
$fileObj = new fileSystem();

$filesVoti = $fileObj->getFileIntoFolder(VOTIDIR);
sort($filesVoti); 

$contenttpl->assign('filesVoti',$filesVoti);

if(isset($_POST['giornata']) && $_POST['giornata'] == TOZIP)
{
	$createZip = new createZip();
	$path = $createZip->createZipFromDir(VOTIDIR,'voti');	
	$createZip->forceDownload($path,"voti.zip");
	@unlink($path); 
}

?>
