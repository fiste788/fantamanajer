<?php 
require_once(INCDIR . 'min.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');

$fileSystemObj = new fileSystem();

$cssFiles = $fileSystemObj->getFileIntoFolderRecursively(CSSDIR,TRUE);
foreach($cssFiles as $key=>$val)
{
	if(is_file($val))
	{
		$array = explode('.',$val);
		$ext = array_pop($array);
		if($ext == 'css') 
			if($array[count($array) - 1] != 'min')
				file_put_contents(implode('.',$array) . '.min.' . $ext , cssmin::minify(file_get_contents($val)));
	}
}
$jsFiles = $fileSystemObj->getFileIntoFolderRecursively(JSDIR,TRUE);
foreach($jsFiles as $key=>$val)
{
	if(is_file($val))
	{
		$array = explode('.',$val);
		$ext = array_pop($array);
		if($ext == 'js') 
			if($array[count($array) - 1] != 'min')
				file_put_contents(implode('.',$array) . '.min.' . $ext,JSMin::minify(file_get_contents($val)));
	}
}
$message->success("Js e css compressi con successo");	
$contentTpl->assign('message',$message);
?>
