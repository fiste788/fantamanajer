<?php 
require_once(INCDIR . 'min.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');

$fileSystemObj = new fileSystem();

$jsFiles = array();
$cssContent = "";
//$cssFiles = $fileSystemObj->getFileIntoFolderRecursively(CSSDIR,TRUE);
$jsFiles = array();
$jsContent = file_get_contents(JSDIR . 'jquery/jquery.js');
$jsContent .= file_get_contents(JSDIR . 'ui/jquery.effects.core.js');
$jsContent .= file_get_contents(JSDIR . 'ui/jquery.effects.pulsate.js');
$jsContent .= file_get_contents(JSDIR . 'custom/all.js');
if(!LOCAL)
	$jsContent .= file_get_contents(JSDIR . 'googleAnalytics/googleAnalytics.js');

foreach($pages as $key=>$val)
{
	if(isset($val['js']) && !empty($val['js']))
	{
		foreach($val['js'] as $directory=>$file)
		{
			if(is_array($file))
			{
				foreach($file as $val)
				{
					if(!in_array($directory . $val,$jsFiles))
					{
						$appo = explode('|',$val);
						if(isset($appo[1]))
						{
							$jsContent .= file_get_contents(JSDIR . $directory . '/' . $appo[1] . '.js');
							$jsFiles[] = $directory . $appo[1];
						}
						else
						{
					//	FB::log($directory . $file);
						$jsContent .= file_get_contents(JSDIR . $directory . '/' . $val . '.js');
						$jsFiles[] = $directory . $val;
						}
					}
				}
			}
			elseif(!in_array($directory . $file,$jsFiles))
			{
				$appo = explode('|',$file);
						if(isset($appo[1]))
						{
							$jsContent .= file_get_contents(JSDIR . $directory . '/' . $appo[1] . '.js');
							$jsFiles[] = $directory . $appo[1];
						}
						else
						{
					//	FB::log($directory . $file);
						$jsContent .= file_get_contents(JSDIR . $directory . '/' . $file . '.js');
						$jsFiles[] = $directory . $file;
						}
			}
			
		}
	}
}

foreach($fileSystemObj->getFileIntoFolderRecursively(JSDIR . 'pages',TRUE) as $key=>$val)
{
	if(is_file($val))
	{
		$array = explode('.',$val);
		$ext = array_pop($array);
		if($ext == 'js') 
			if($array[count($array) - 1] != 'min')
				$jsContent .= file_get_contents($val);
	}
}
foreach($fileSystemObj->getFileIntoFolderRecursively(CSSDIR,TRUE) as $key=>$val)
{
	if(is_file($val))
	{
		$array = explode('.',$val);
		$ext = array_pop($array);
		if($ext == 'css') 
			if($array[count($array) - 1] != 'min')
			{
				$appo = explode('/',$array[0]);
				$file = array_pop($appo);
				if($file != 'combined')
				{
					if($file == 'ui')
						file_put_contents(implode('.',$array) . '.min.' . $ext , cssmin::minify(file_get_contents($val)));
					elseif($file != 'ie')
						$cssContent .= file_get_contents($val);
					else
						file_put_contents(implode('.',$array) . '.min.' . $ext , cssmin::minify(file_get_contents($val)));
				}
			}
	}
}
file_put_contents(JSDIR . 'combined/combined.js' , JSMin::minify($jsContent));
file_put_contents(CSSDIR . 'combined.css' , cssmin::minify($cssContent));

$message->success("Js e css compressi con successo");	
$contentTpl->assign('message',$message);
?>
