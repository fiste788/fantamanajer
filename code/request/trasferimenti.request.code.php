<?php
require_once(INCDBDIR . "trasferimento.db.inc.php");
require_once(INCDBDIR . "selezione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");

if(($selezione = Selezione::getByField('idUtente',$_SESSION['idUtente'])) == FALSE)
	$selezione = new Selezione();

if($_SESSION['logged']){
	if(Request::getInstance()->get('submit') == 'Cancella acq.') {
		Selezione::unsetSelezioneByIdSquadra($_SESSION['idUtente']);
		$message->success('Cancellazione eseguita con successo');
	} else {
		if($selezione->getIdGiocatoreNew() != Request::getInstance()->get('idGiocatoreNew') || $selezione->getIdGiocatoreOld() != Request::getInstance()->get('idGiocatoreOld'))
			$selezione->setNumSelezioni($selezione->getNumSelezioni() + 1);
		if($selezione->validate()) {
			$squadraOld = Selezione::checkFree($selezione->getIdGiocatoreNew(),$_SESSION['idLega']);
			$firePHP->log($squadraOld);
			if($squadraOld != FALSE && $squadraOld != $_SESSION['idUtente']) {
				$posizioni = Punteggio::getPosClassifica($_SESSION['idLega']);
				if($posizioni[$_SESSION['idUtente']] < $posizioni[$squadraOld]) {
					//Selezione::updateGioc($acquisto,$lasciato,$_SESSION['idLega'],$_SESSION['idUtente']);
					$mailContent->assign('giocatore',$selezione->getGiocatoreNew()->nome . ' ' . $selezione->getGiocatoreNew()->cognome);
					$appo = $squadre[$acquistoDett->idSquadraAcquisto];
					Mail::sendEmail($squadre[$appo]->mail,$mailContent->fetch(MAILTPLDIR . 'mailGiocatoreRubato.tpl.php'),'Giocatore rubato!');
				} else
					$message->warning('Un altra squadra inferiore di te ha già selezionato questo giocatore');
			}
			$selezione->setIdLega($_SESSION['idLega']);
			if($selezione->save()) {
			    $evento = new Evento();
			    $evento->setTipo(Evento::SELEZIONEGIOCATORE);
			    $evento->setIdUtente($_SESSION['idUtente']);
			    $evento->setIdLega($_SESSION['idLega']);
				$evento->save();
				$message->success('Operazione eseguita con successo');
			} else
				$message->error("Errore generico nell'inserimento");
		}
	}
}
$contentTpl->assign('selezione',$selezione);
?>
