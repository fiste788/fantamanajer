<?php 
require_once(INCDIR."articolo.inc.php");
require_once(INCDIR."emoticon.inc.php");
require_once(INCDIR."eventi.inc.php");

$articoloObj = new articolo();
$emoticonObj = new emoticon();
$eventiObj = new eventi();

$action = NULL;
$field = array('title'=>'Titolo','abstract'=>'Sottotitolo','text'=>'Testo');
if(isset($_GET['a']))
	$action = $_GET['a'];

$contenttpl->assign('emoticons',$emoticonObj->emoticon);
$contenttpl->assign('messaggio',NULL);

if($action == 'edit' || $action == 'cancel')
{
	$articoloObj->setidArticolo($_GET['id']);
	$articolo = $articoloObj->select($articoloObj,'=','*');
	$contenttpl->assign('articolo',$articolo);
}

if(isset($_POST['submit']))
{
	if($action == 'cancel')
	{
		$articoloObj->delete($articoloObj);
		$messaggio[0] = 0;
		$eventiObj->deleteEventoByIdExternalAndTipo($_GET['id'],'1');
		$messaggio[] = 'Cancellazione effettuata con successo';
		$contenttpl->assign('messaggio',$messaggio);
		$_SESSION['message'] = $messaggio;
		header("Location: ".$contenttpl->linksObj->getLink('conferenzeStampa'));
	}

	if($action == 'new' || $action == 'edit')
	{
		//INSERISCO NEL DB OPPURE SEGNALO I CAMPI NON RIEMPITI
		if ( (isset($_POST['title'])) && (!empty($_POST['title'])) && (isset($_POST['text'])) && (!empty($_POST['text'])) )
		{
			$articoloObj = new articolo();
			$articoloObj->settitle(addslashes(stripslashes($_POST['title'])));
			$articoloObj->setabstract(addslashes(stripslashes($_POST['abstract'])));
			$articoloObj->settext(addslashes(stripslashes($_POST['text'])));
			if($action == 'new')
			{
				$articoloObj->setinsertdate(date("Y-m-d H:i:s"));
				$articoloObj->setidgiornata($giornata);
			}
			else
			{
				$articoloObj->setinsertdate($articolo[0]['insertDate']);
				$articoloObj->setidgiornata($articolo[0]['idGiornata']);
			}
			$articoloObj->setidsquadra($_SESSION['idsquadra']);
			if($action == 'new')
			{
				$idArticolo = $articoloObj->add($articoloObj);
				$messaggio[0] = 0;
				$messaggio[] = "Inserimento completato con successo!";
				$contenttpl->assign('messaggio',$messaggio);
				$eventiObj->addEvento('1',$_SESSION['idsquadra'],$idArticolo);
			}
			else
			{
				$articoloObj->setidArticolo($_GET['id']);
				$articoloObj->update($articoloObj);
				$messaggio[0] = 0;
				$messaggio[] = "Modifica effettuata con successo!";
				$contenttpl->assign('messaggio',$messaggio);
			}
			$_SESSION['message'] = $messaggio;
			header("Location: ". $contenttpl->linksObj->getLink('conferenzeStampa'));
		}
		else
		{
			$messaggio[0] = 1;
			$messaggio[] = "Non hai compilato correttamente tutti i campi";
			$contenttpl->assign('messaggio',$messaggio);
		}
	}
}

?>
