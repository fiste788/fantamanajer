<?php 
class fileSystem
{
	function getDirIntoFolder($folder) 
	{
		$output = array();
		if ($handle = opendir($folder)) 
		{
			while (false !== ($file = readdir($handle)))
			{ 
				if ($file != "." && $file != ".." && $file != ".svn" && is_dir($folder.'/'.$file)) 
					$output[] = $file;
			} 
			closedir($handle); 
			return $output;
		}
		else
		{
			return "La cartella ".$folder." non esiste";
			die;
		}
	}
	
	function getFileIntoFolder($folder) 
	{
		$output = array();
		if ($handle = opendir($folder)) 
		{
			while (false !== ($file = readdir($handle)))
			{ 
				if ($file != "." && $file != ".." && $file != ".svn") 
					$output[] = $file;
			} 
			closedir($handle); 
			return $output;
		}
		else
		{
			return "La cartella ".$folder." non esiste";
			die;
		}
	}
}
?>
