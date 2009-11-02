<?php 
require_once(INCDIR.'fileSystem.inc.php');

$fileSystemObj = new fileSystem();

$root = ".";
$files = directoryToArray($root, true);
$path = "httpdocs/test/";
function directoryToArray($directory, $recursive) {
	$array_items = array();
	$connection = ftp_connect("develop.fantamanajer.it", 21) or die('Server non disponibile.');
	ftp_login($connection, "developuser", "fantadevelop") or die('Username o password errati.');

	if ($handle = opendir($directory)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != ".svn") {
				if (is_dir($directory. "/" . $file)) {
					
					$array_items[] = preg_replace("/\/\//si", "/", $directory . "/" . $file);
					//ftp_mkdir($connection, $file);
					if($recursive) {
						$array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
					}
					$file = $directory . "/" . $file;
				} else {
					$file = $directory . "/" . $file;
					echo $file;
					echo mime_content_type($file);
					/*if(substr(mime_content_type($file), 4) == 'text')
						echo ftp_put($connection, $path.$file, $file, FTP_ASCII);
					else
						echo ftp_put($connection, $path.$file, $file, FTP_BINARY);*/
					$array_items[] = preg_replace("/\/\//si", "/", $file);
				}
			}
		}
		closedir($handle);
	}
	return $array_items;
}
 
 echo "<pre>".print_r($files,1)."</pre>";


?>
