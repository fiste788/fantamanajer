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
		$titolari = $request->getRawData('post','titolari');
		$panchinari = $request->getRawData('post','panchinari');
		$formazione->setIdGiornata(GIORNATA);
		$formazione->setIdUtente($_SESSION['idUtente']);
			if($request->get('C') != 0)
				$formazione->setIdCapitano($request->get('C'));
			if($request->get('VC') != 0)
				$formazione->setIdVCapitano($request->get('VC'));
			if($request->get('VVC') != 0)
				$formazione->setIdVVCapitano($request->get('VVC'));
		if($formazione->save(array('titolari'=>$titolari,'panchinari'=>$panchinari))) {
			$message->success('Formazione caricata correttamente');
		}
    }
}
?>
