<?php
require_once(INCDIR . 'upload.inc.php');

//set type of file supported
$img = array ("image/gif" , "image/png" , "image/pjpeg" , "image/jpeg");
$vid = array ();
$doc = array ();
$size = 500000;		//set the max size for the upload file
$width_thumb = 240;
$height_thumb = 300;
$width_med_thumb = 124;
$height_med_thumb = 93;
$width_large_thumb = 800;
$height_large_thumb = 600;
$image_type = 1;
switch($image_type) 
{
	case 1: $exts = '.jpg'; break;
	case 2: $exts = '.gif'; break;
	case 3: $exts = '.png'; break;
}
if (isset ($_FILES ['userfile']['tmp_name']) && !empty($_FILES['userfile']['tmp_name']))
{
	$ext = upload::getExtension($_FILES ['userfile']['name']);
	if(isset($_SESSION['idSquadra']))
		$name = $_SESSION['idSquadra'];
	switch( upload::uploadFile($size , $img , $vid , $doc, UPLOADDIR , $name . '-temp'))
	{
			case 0: if(upload::resize($name , UPLOADDIR , $width_thumb , $height_thumb , UPLOADDIR . $name . '-temp.' . $ext, $image_type) && 
						upload::resize($name . '-med' , UPLOADDIR , $width_med_thumb , $height_med_thumb , UPLOADDIR . $name . '-temp.' . $ext, $image_type) && 
						upload::resize($name . '-original' , UPLOADDIR , $width_large_thumb , $height_large_thumb , UPLOADDIR . $name . '-temp.' . $ext, $image_type))
							$message->success("Upload effettuato correttamente, Potrebbe essere necessario ricaricare la pagina per problemi di cache. Premi Ctrl+R");
					else
						$message->warning("Problemi nel ridimensionamento");
					unlink(UPLOADDIR . $name . '-temp.' . $ext);
					break;
			case 1: $message->error("Nessun file selezionato"); break;
			case 2: $message->warning("File troppo grande"); break;
			case 3: $message->warning("Tipo di file non supportato"); break;
			case 4: $message->error("Errore nell'upload del file"); break;
	}
}
?>
