<?php 
require_once(INCDIR . "articolo.db.inc.php");
require_once(INCDIR . "evento.db.inc.php");
require_once(INCDIR . "emoticon.inc.php");

$articoloObj = new articolo();
$eventoObj = new evento();
$emoticonObj = new emoticon();

$filterAction = NULL;
$filterId = NULL;
if(isset($_GET['a']))
	$filterAction = $_GET['a'];
if(isset($_GET['id']))
	$filterId = $_GET['id'];

if($filterAction == 'edit' || $filterAction == 'cancel')
{
	$articoloObj->setidArticolo($filterId);
	$articolo = $articoloObj->select($articoloObj,'=','*');
	$contentTpl->assign('articolo',$articolo);
}

if(isset($_POST['submit']))
{
	if($filterAction == 'cancel')
	{
		$articoloObj->delete($articoloObj);
		$eventoObj->deleteEventoByIdExternalAndTipo($filterId,'1');
		$message->success('Cancellazione effettuata con successo');
		$_SESSION['message'] = $message;
		header("Location: " . $contentTpl->linksObj->getLink('conferenzeStampa'));
	}

	if($filterAction == 'new' || $filterAction == 'edit')
	{
		//INSERISCO NEL DB OPPURE SEGNALO I CAMPI NON RIEMPITI
		if ( (isset($_POST['title'])) && (!empty($_POST['title'])) && (isset($_POST['text'])) && (!empty($_POST['text'])) )
		{
			$articoloObj = new articolo();
			$articoloObj->settitle(addslashes(stripslashes($_POST['title'])));
			$articoloObj->setabstract(addslashes(stripslashes($_POST['abstract'])));
			$articoloObj->settext(addslashes(stripslashes($_POST['text'])));
			if($filterAction == 'new')
			{
				$articoloObj->setinsertdate(date("Y-m-d H:i:s"));
				$articoloObj->setidgiornata(GIORNATA);
			}
			else
			{
				$articoloObj->setinsertdate($articolo[0]->insertDate);
				$articoloObj->setidgiornata($articolo[0]->idGiornata);
			}
			$articoloObj->setidsquadra($_SESSION['idSquadra']);
			$articoloObj->setidlega($_SESSION['idLega']);
			if($filterAction == 'new')
			{
				$idArticolo = $articoloObj->add($articoloObj);
				$message->success("Inserimento completato con successo");
				$eventoObj->addEvento('1',$_SESSION['idSquadra'],$_SESSION['idLega'],$idArticolo);
			}
			else
			{
				$articoloObj->setidArticolo($filterId);
				$articoloObj->update($articoloObj);
				$message->success("Modifica effettuata con successo");
			}
			$_SESSION['message'] = $message;
			header("Location: ". $contentTpl->linksObj->getLink('conferenzeStampa'));
		}
		else
			$message->error("Non hai compilato correttamente tutti i campi");
	}
}
$title = "";
$abstract = "";
$text = "";
if(isset($articolo))
	$title = $articolo[0]->title;
if(isset($_POST['title']))
	$title = $_POST['title'];
if(isset($articolo))
	$abstract = $articolo[0]->abstract;
if(isset($_POST['abstract']))
	$abstract = $_POST['abstract'];
if(isset($articolo))
	$text = $articolo[0]->text;
if(isset($_POST['text']))
	$text = $_POST['text'];
switch($filterAction)
{
	case 'cancel': $button = 'Rimuovi'; break;
	case 'edit': $button = 'Modifica'; break; 
	case 'new': $button = 'Inserisci'; break;
	default: $button = 'Errore';break;
}
$goTo = array('a'=>$filterAction);
if($filterId != NULL) 
	$goTo['id'] = $filterId;

$contentTpl->assign('action',$filterAction);
$contentTpl->assign('title',$title);
$contentTpl->assign('abstract',$abstract);
$contentTpl->assign('text',$text);
$contentTpl->assign('emoticons',$emoticonObj->emoticon);
$contentTpl->assign('button',$button);
$contentTpl->assign('goTo',$goTo);
?>
