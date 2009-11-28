<?php
require_once(INCDIR . 'upload.inc.php');
$uploadObj = new upload();

//set type of file supported
$img = array ("image/gif" , "image/png" , "image/pjpeg" , "image/jpeg");
$vid = array ();
$doc = array ();
$size = 500000;		//set the max size for the upload file
$path = 'uploadimg/' ;
$width_thumb = 240;
$height_thumb = 300;
$width_med_thumb = 120;
$height_med_thumb = 90;
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
	$ext = $uploadObj->getExtension($_FILES ['userfile']['name']);
	if(isset($_SESSION['idSquadra']))
		$name = $_SESSION['idSquadra'];
	switch( $uploadObj->uploadFile ($size , $img , $vid , $doc, $path , $name . '-temp'))
	{
			case 0: if($uploadObj->resize($name , $path , $width_thumb , $height_thumb , $path . $name . '-temp.' . $ext, $image_type) && 
						$uploadObj->resize($name . '-med' , $path , $width_med_thumb , $height_med_thumb , $path . $name . '-temp.' . $ext, $image_type) && 
						$uploadObj->resize($name . '-original' , $path , $width_large_thumb , $height_large_thumb , $path . $name . '-temp.' . $ext, $image_type))
					{
						$message['level'] = 0;
						$message['text'] = "Upload effettuato correttamente, Potrebbe essere necessario ricaricare la pagina per problemi di cache. Premi Ctrl+R";
					}
					else
					{
						$message['level'] = 1;
						$message['text'] = "Problemi nel ridimensionamento";
					}
					unlink($path . $name . '-temp.' . $ext);
					break;
			case 1: $message['level'] = 1;
					$message['text'] = "Nessun file selezionato"; break;
			case 2: $message['level'] = 1;
					$message['text'] = "File troppo grande"; break;
			case 3: $message['level'] = 1;
					$message['text'] = "Tipo di file non supportato"; break;
			case 4: $message['level'] = 1;
					$message['text'] = "Errore nell'upload del file"; break;
	}
	$layouttpl->assign('message',$message);
}
?>
