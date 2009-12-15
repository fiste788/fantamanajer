<?php 
require_once(INCDIR . 'fileSystem.inc.php');
include_once(INCDIR . 'createZip.inc.php');

$fileObj = new fileSystem();

$filesVoti = $fileObj->getFileIntoFolder(VOTIDIR);
sort($filesVoti); 

$contenttpl->assign('filesVoti',$filesVoti);

if(isset($_POST['giornata']) && !empty($_POST['giornata']))
{
	if($_POST['giornata'] == "all")
	{
		$createZip = new createZip();
		$path = $createZip->createZipFromDir(VOTIDIR,'voti');
		$createZip->forceDownload($path,"voti.zip");
		@unlink($path);
	}
	else
	{
		header("Content-type: text/csv");
		header("Content-Disposition: attachment;filename=" . basename($_POST['giornata']));
		header("Content-Transfer-Encoding: binary");
		header("Expires: 0");
		header("Pragma: no-cache");
		readfile(VOTIDIR . $_POST['giornata']);
	}
	die();
}
?>
