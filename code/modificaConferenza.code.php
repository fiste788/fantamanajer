<?php 
require_once(INCDIR . "articolo.db.inc.php");
require_once(INCDIR . "evento.db.inc.php");
require_once(INCDIR . "emoticon.inc.php");

$filterAction = NULL;
$filterId = NULL;
if(isset($_GET['a']))
	$filterAction = $_GET['a'];
if(isset($_GET['id']))
	$filterId = $_GET['id'];

if($filterAction == 'edit' || $filterAction == 'cancel')
{
	$articolo = Articolo::getArticoloById($filterId);
	$contentTpl->assign('articolo',$articolo);
}

if(isset($_POST['submit']))
{
	if($filterAction == 'cancel')
	{
		Articolo::deleteArticolo($filterId);
		Evento::deleteEventoByIdExternalAndTipo($filterId,'1');
		$message->success('Cancellazione effettuata con successo');
		$_SESSION['message'] = $message;
		header("Location: " . Links::getLink('conferenzeStampa'));
	}

	if($filterAction == 'new' || $filterAction == 'edit')
	{
		//INSERISCO NEL DB OPPURE SEGNALO I CAMPI NON RIEMPITI
		if ( (isset($_POST['title'])) && (!empty($_POST['title'])) && (isset($_POST['text'])) && (!empty($_POST['text'])) )
		{
			if($filterAction == 'new')
			{
				$idArticolo = Articolo::addArticolo(addslashes(stripslashes($_POST['title'])),addslashes(stripslashes($_POST['abstract'])),addslashes(stripslashes($_POST['text'])),$_SESSION['idSquadra'],GIORNATA,$_SESSION['idLega']);
				$message->success("Inserimento completato con successo");
				Evento::addEvento('1',$_SESSION['idSquadra'],$_SESSION['idLega'],$idArticolo);
			}
			else
			{
				Articolo::updateArticolo($filterId,addslashes(stripslashes($_POST['title'])),addslashes(stripslashes($_POST['abstract'])),addslashes(stripslashes($_POST['text'])),$_SESSION['idSquadra'],$_SESSION['idLega']);
				$message->success("Modifica effettuata con successo");
			}
			$_SESSION['message'] = $message;
			header("Location: " . Links::getLink('conferenzeStampa'));
		}
		else
			$message->error("Non hai compilato correttamente tutti i campi");
	}
}
$title = "";
$abstract = "";
$text = "";
FB::log($articolo);
if(isset($articolo))
	$title = $articolo->title;
if(isset($_POST['title']))
	$title = $_POST['title'];
if(isset($articolo))
	$abstract = $articolo->abstract;
if(isset($_POST['abstract']))
	$abstract = $_POST['abstract'];
if(isset($articolo))
	$text = $articolo->text;
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
$contentTpl->assign('emoticons',Emoticon::$emoticon);
$contentTpl->assign('button',$button);
$contentTpl->assign('goTo',$goTo);
?>
