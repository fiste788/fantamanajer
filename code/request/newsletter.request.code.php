<?php 
require_once(INCDBDIR . 'articolo.db.inc.php');
require_once(INCDBDIR . 'evento.db.inc.php');
require_once(INCDIR . "form/newsletter.form.inc.php");
require_once(INCDIR . 'swiftMailer/swift_required.php');

$transportObj = Swift_MailTransport::newInstance();
$mailerObj = Swift_Mailer::newInstance($transportObj);
$mailContent = new Savant3();

$newsletter = new NewsletterForm();
if($newsletter->validate()) {
	$object = (($newsletter->type == 'C') ? 'Comunicazione: ' : 'Newsletter') . $newsletter->object;
	$mailContent->assign('object',$object);
	$mailContent->assign('text',nl2br($newsletter->text));
	$mailContent->assign('date',date("d-m-Y"));
	$mailContent->assign('type',$newsletter->type);
	$mailContent->assign('autore',Utente::getById($_SESSION['idUtente']));
	$mailContent->setFilters(array("Savant3_Filter_trimwhitespace","filter"));
	$utenti = Utente::getByIds($newsletter->selezione);
	foreach($utenti as $key=>$utente) {
		if($newsletter->type == 'C' || ($newsletter->type != 'C' && $utente->abilitaMail))
			$email[] = array($utente->mail=>$utente->cognome . ' ' . $utente->nome);
	}
	
	$bool = TRUE;

	$mailMessageObj = Swift_Message::newInstance();
	$mailMessageObj->setSubject($object);
	$mailMessageObj->setFrom(array("noreply@fantamanajer.it"=>"FantaManajer"));
	$fetchMail = $mailContent->fetch(MAILTPLDIR . 'mailNewsletter.tpl.php');
	$mailMessageObj->setBody($fetchMail,'text/html');

	foreach($email as $key => $val){
		$mailMessageObj->setTo($val);
		$bool *= $mailerObj->send($mailMessageObj);
	}
	if($bool)
	{
		if(isset($_POST['conferenza']))
		{
			Articolo::addArticolo($_POST['object'],"",$_POST['text'],$_SESSION['idUtente'],GIORNATA,$_SESSION['idLega']);
			Evento::addEvento('1',$_SESSION['idUtente'],$_SESSION['idLega'],$idArticolo);
		}
		$message->success('Mail inviate correttamente');
	}
	else
		$message->error('Problemi nell\'invio delle mail');
}
 	
$contentTpl->assign('newsletter',$newsletter)
?>
