<?php 
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(INCDBDIR . "giocatore.db.inc.php");
require_once(INCDBDIR . "punteggio.db.inc.php");
require_once(INCDBDIR . "voto.db.inc.php");

$filterUtente = $request->has('idUtente') ? $request->get('idUtente') : NULL;
$filterGiornata = $request->has('idGiornata') ? $request->get('idGiornata') : NULL;
$filterLega = $request->has('idLega') ? $request->get('idLega') : NULL;
if($_SESSION['usertype'] == 'admin')
	$filterLega = $_SESSION['idLega'];

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
			if(Voto::checkVotiExist($filterGiornata)) {
				Punteggio::unsetPenalità($filterSquadra,$filterGiornata);
				Punteggio::unsetPunteggio($filterSquadra,$filterGiornata);
				Punteggio::calcolaPunti($filterGiornata,$filterSquadra,$filterLega);
				/*$mailContent->assign('giornata',$filterGiornata);
			$mailContent->assign('squadra',$squadraDett->nome);
			$mailContent->assign('somma',$punteggiObj->getPunteggi($squadra,$giornata));
			$mailContent->assign('formazione',$giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($giornata,$squadra));

			$object = "Giornata: ". $giornata . " - Punteggio: " . $punteggiObj->getPunteggi($squadra,$giornata);
			//$mailContent->display(TPLDIR.'mail.tpl.php');
			$mailObj->sendEmail($squadraDett['nomeProp'] . " " . $squadraDett['cognome'] . "<" . $squadraDett['mail']. ">",$mailContent->fetch(TPLDIR.'mail.tpl.php'),$object);*/
			}
			$message->success('Formazione caricata correttamente');
		}
    }
}
?>
