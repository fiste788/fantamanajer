<?php 
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(INCDBDIR . "giocatore.db.inc.php");
require_once(INCDBDIR . "punteggio.db.inc.php");

$filterUtente = $request->has('utente') ? $request->get('utente') : $_SESSION['idUtente'];
$filterGiornata = $request->has('giornata') ? $request->get('giornata') : GIORNATA;

if(!PARTITEINCORSO)
{
	if($filterGiornata == GIORNATA && $filterUtente == $_SESSION['idUtente']) {
		$formazione = Formazione::getFormazioneBySquadraAndGiornata($filterUtente,$filterGiornata);
		if(!$formazione)
			$formazione = new Formazione();
		if($formazione->validate()) {
			$success = TRUE;
			$giocatoriIds = array();
			$modulo = array('P'=>0,'D'=>0,'C'=>0,'A'=>0);
			$titolari = $request->getRawData('post','gioc');
			$panchinari = $request->getRawData('post','panch');
			$giocatoriIds = array_merge($giocatoriIds,$titolari,$panchinari);
			$giocatori = Giocatore::getByIds($giocatoriIds);
			foreach($titolari as $key=>$titolare)
				$modulo[$giocatori[$titolare]->ruolo] += 1;
			$formazione->setIdGiornata(GIORNATA);
			$formazione->setIdUtente($_SESSION['idUtente']);
			$formazione->setModulo(implode($modulo,'-'));
			$formazione->startTransaction();
			if(($idFormazione = $formazione->save()) != FALSE) {
				$schieramenti = Schieramento::getSchieramentoById($idFormazione);
				foreach($giocatoriIds as $posizione=>$idGiocatore) {
					$schieramento = isset($schieramenti[$posizione]) ? $schieramenti[$posizione] : new Schieramento();
					if(!is_null($idGiocatore) && !empty($idGiocatore)) {
						if($schieramento->idGiocatore != $idGiocatore) {
							$schieramento->setIdFormazione($idFormazione);
							$schieramento->setPosizione($posizione + 1);
							$schieramento->setIdGiocatore($idGiocatore);
							$schieramento->setConsiderato(0);
							$success = ($success and $schieramento->save());
						}
					} else
						$success = ($success and $schieramento->delete());
				}
				if($success)
					$formazione::commit();
				else
					$formazione::rollback();
			}
	    }
}
}
?>
