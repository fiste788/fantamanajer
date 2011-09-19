<?php 
require_once(INCDIR . 'min.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');

$jsContent = "";
foreach($generalJs as $val)
	$jsContent .= file_get_contents(JSDIR . $val);

if(!LOCAL)
	$jsContent .= file_get_contents(JSDIR . 'googleAnalytics/googleAnalytics.js');
	
file_put_contents(JSDIR . 'combined/combined.js' , JSMin::minify($jsContent));

foreach($pages as $key=>$val)
{
	$jsContent = "";
	if(isset($val['js']) && !empty($val['js']))
	{
		foreach($val['js'] as $directory=>$file)
		{
			if(is_array($file))
			{
				foreach($file as $val)
				{
					$appo = explode('|',$val);
					if(isset($appo[1]))
						$jsContent .= file_get_contents(JSDIR . $directory . '/' . $appo[1] . '.js');
					else
						$jsContent .= file_get_contents(JSDIR . $directory . '/' . $val . '.js');
				}
			}
			else
			{
				$appo = explode('|',$file);
				if(isset($appo[1]))
					$jsContent .= file_get_contents(JSDIR . $directory . '/' . $appo[1] . '.js');
				else
					$jsContent .= file_get_contents(JSDIR . $directory . '/' . $file . '.js');
			}
		}
	}
	if(file_exists(JSDIR . 'pages/' . $key . '.js'))
		$jsContent .= file_get_contents(JSDIR . 'pages/' . $key . '.js');
	if(!empty($jsContent))
		file_put_contents(JSDIR . 'combined/' . $key . '.js' , JSMin::minify($jsContent));
}
$cssContent = "";
foreach(fileSystem::getFileIntoFolderRecursively(CSSDIR,TRUE) as $key=>$val)
{
	if(is_file($val))
	{
		$appo = explode('/',$val);
		$file = array_pop($appo);
		$array = explode('.',$file);
		$ext = array_pop($array);
		if($ext == 'css') 
			if($array[count($array) - 1] != 'min')
			{
				$nomeFile = $array[0];
				if($nomeFile != 'combined')
				{
					if($nomeFile == 'ui')
						file_put_contents(implode('/',$appo) . '/' . implode('.',$array) . '.min.' . $ext , cssmin::minify(file_get_contents($val)));
					elseif($nomeFile != 'ie')
						$cssContent .= file_get_contents($val);
					else
						file_put_contents(implode('/',$appo) . '/' . implode('.',$array) . '.min.' . $ext , cssmin::minify(file_get_contents($val)));
				}
			}
	}
}
file_put_contents(CSSDIR . 'combined.css' , cssmin::minify($cssContent));
file_put_contents(DOCSDIR . 'staticVersion.txt' , VERSION + 1);

$message->success("Js e css compressi con successo");	
$contentTpl->assign('message',$message);
?>
