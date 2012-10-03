<?php 
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(INCDBDIR . "giocatore.db.inc.php");
require_once(INCDBDIR . "punteggio.db.inc.php");

$filterUtente = $_SESSION['idUtente'];
$filterGiornata = GIORNATA;

if(!PARTITEINCORSO)
{
	$formazione = Formazione::getFormazioneBySquadraAndGiornata($filterUtente,$filterGiornata);
	if(!$formazione)
		$formazione = new Formazione();
	if($formazione->validate()) {
		$titolari = Request::getInstance()->getRawData('post','titolari');
		$panchinari = Request::getInstance()->getRawData('post','panchinari');
		$formazione->setIdGiornata(GIORNATA);
		$formazione->setIdUtente($_SESSION['idUtente']);
			if(Request::getInstance()->get('C') != 0)
				$formazione->setIdCapitano(Request::getInstance()->get('C'));
			if(Request::getInstance()->get('VC') != 0)
				$formazione->setIdVCapitano(Request::getInstance()->get('VC'));
			if(Request::getInstance()->get('VVC') != 0)
				$formazione->setIdVVCapitano(Request::getInstance()->get('VVC'));
		if($formazione->save(array('titolari'=>$titolari,'panchinari'=>$panchinari))) {
			$message->success('Formazione caricata correttamente');
		}
    }
}
?>
