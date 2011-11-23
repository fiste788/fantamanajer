<?php 
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'utente.db.inc.php');

if(isset($_POST))
{
	if(!empty($_FILES ['userfile']['tmp_name']))
	{
		require_once(INCDIR . 'upload.inc.php');	
		$img = array ("image/gif" , "image/png" , "image/pjpeg" , "image/jpeg");
		$vid = array();
		$doc = array();
		$size = 500000;		//set the max size for the upload file
		$width_thumb = 160;
		$height_thumb = 200;
		$image_type = 1;
		switch($image_type) 
		{
			case 1: $exts = '.jpg'; break;		// the last argument indicate the compression of the image
			case 2: $exts = '.gif'; break;
			case 3: $exts = '.png'; break;
			default:die("Parametro mancante o errato");
		}
		$ext = upload::getExtension($_FILES ['userfile']['name']);
		if(isset($_POST['idGioc']))
			$name = $_POST['idGioc'];

		switch(upload::uploadFile($size , $img , $vid , $doc, PLAYERSDIR , $name.'-temp'))
		{
				case 0: 	switch (strtolower(upload::getExtension(PLAYERSDIR . $name . '-temp.' . $ext)))			//switch for get the extension
								{
										case 'jpg' : $image = imagecreatefromjpeg(PLAYERSDIR . $name . '-temp.' . $ext); break;
										case 'gif' : $image = imagecreatefromgif(PLAYERSDIR . $name . '-temp.' . $ext); break;
										case 'png' : $image = imagecreatefrompng(PLAYERSDIR . $name . '-temp.' . $ext); break;
										default : die("File non supportato");
								}		
								$width = imagesx ($image);
								if($width > $width_thumb)
								{
									if(upload::resize($name , PLAYERSDIR , $width_thumb , $height_thumb , PLAYERSDIR . $name . '-temp.' . $ext, $image_type) )
									{
										$message->success('Upload effettuato correttamente');
										unlink(PLAYERSDIR . $name . '-temp.' . $ext);
									}
									else
										$message->waring('Problemi nel ridimensionamento');
								}
								else
								{
									$nameimg = PLAYERSDIR . $name . "." . $ext;
									if(file_exists($nameimg))
										unlink($nameimg);
									rename(PLAYERSDIR . $name . '-temp.' . $ext,$nameimg);
									$message->success('Upload effettuato correttamente');
								}
								
								break;
				case 1: 	$message->warning('Nessun file selezionato'); break;
				case 2: 	$message->warning('File troppo grande'); break;
				case 3: 	$message->warning('Tipo di file non supportato'); break;
				case 4: 	$message->error('Errore nell\'upload del file'); break;
		}
	}

	if(!empty($_POST['nome']) && !empty($_POST['nome']))
	{
		Giocatore::aggiornaGiocatore($_POST['idGioc'],addslashes($_POST['cognome']),addslashes($_POST['nome']));
		$message->success("Giocatore modificato correttamente");
	}
}
$operationTpl->assign('giocatori',Giocatore::getList());
?>
