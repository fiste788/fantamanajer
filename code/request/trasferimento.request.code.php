<?php
require_once(INCDBDIR . "trasferimento.db.inc.php");
require_once(INCDBDIR . "selezione.db.inc.php");

$filterId = Request::getInstance()->has('id') ? Request::getInstance()->get('id') : $_SESSION['idUtente'];
$selezione = Selezione::getSelezioneByIdSquadra($_SESSION['idUtente']);
if($_SESSION['logged']) {
	if($numTrasferimenti < $_SESSION['datiLega']->numTrasferimenti ) {
		if(!empty($selezione)) {
			$acquistoId = $selezione->giocatoreNew;
			$lasciatoId = $selezione->giocatoreOld;
		}
		if(Request::getInstance()->has('acquista'))
			$acquistoId = Request::getInstance()->get('acquista');
		if(Request::getInstance()->has('lascia'))
			$lasciatoId = Request::getInstance()->get('lascia');

		if(Request::getInstance()->get('submit') == 'Cancella acq.')
		{
			//$selezione->delete();
			Selezione::unsetSelezioneByIdSquadra($_SESSION['idUtente']);
			$message->success('Cancellazione eseguita con successo');
			$acquistoId = NULL;
			$lasciatoId = NULL;
		}

		$acquisto = Giocatore::getById($acquistoId);
		$lasciato = Giocatore::getById($lasciatoId);
		$numSelezioni = Selezione::getNumberSelezioni($filterSquadra);

		if(Request::getInstance()->get('submit') == 'OK')
		{
			if($lasciato->ruolo == $acquisto->ruolo)
			{
				if($acquistoId != "" && $lasciatoId != "")
				{
					if($selezione == FALSE || ($selezione != FALSE && ($selezione->giocatoreNew != $acquistoId) || ($selezione->giocatoreOld != $lasciatoId)))
					{
						if($numSelezioni < $_SESSION['datiLega']->numSelezioni)
						{
							$filterSquadraOld = Selezione::checkFree($acquisto,$_SESSION['idLega']);
							if($filterSquadraOld != FALSE)
							{
								$posizioni = Punteggio::getPosClassifica($_SESSION['idLega']);
								if($posizioni[$_SESSION['idUtente']] > $posizioni[$filterSquadraOld])
								{
									$squadre = Utente::getByField('idLega',$_SESSION['legaView']);
									Selezione::updateGioc($acquisto,$lasciato,$_SESSION['idLega'],$_SESSION['idUtente']);
									$mailContent->assign('giocatore',$acquistoDett->nome . ' ' . $acquistoDett->cognome);
									$appo = $squadre[$acquistoDett->idSquadraAcquisto];
									Mail::sendEmail($squadre[$appo]->mail,$mailContent->fetch(MAILTPLDIR . 'mailGiocatoreRubato.tpl.php'),'Giocatore rubato!');
								}
								else
								{
									$message->warning('Un altra squadra inferiore di te ha già selezionato questo giocatore');
									$acquisto = NULL;
									$lasciato = NULL;
									$flag = 1;
								}
							}
							else
							{
								$selezione->setIdGiocatoreNew($acquisto);
								$selezione->setIdGiocatoreOld($lasciato);
								if($selezione->save()) {
									Evento::addEvento(Evento::SELEZIONEGIOCATORE,$_SESSION['idUtente'],$_SESSION['idLega']);
									$message->success('Operazione eseguita con successo');
								}
							}
						}
						else
						{
							$flag = 1;
							$message->warning('Hai già cambiato ' . $_SESSION['datiLega']->numSelezioni . ' volte il tuo acquisto');
						}
					}
					else
						$message->warning('Hai già selezionato questi giocatori per l\'acquisto');
				}
				else
					$message->error('Non hai compilato correttamente');
			}
			else
				$message->error('I giocatori devono avere lo stesso ruolo');
		}
		if($flag == 1 && $selezione != FALSE)
		{
			$acquisto = $selezione->idGiocatoreNew;
			$lasciato = $selezione->idGiocatoreOld;
		}
		$contentTpl->assign('giocAcquisto',$acquistoId);
		$contentTpl->assign('giocLasciato',$lasciatoId);

		$giocatoreAcquistatoOld = Selezione::getSelezioneByIdSquadra($_SESSION['idUtente']);
		if(!empty($giocatoreAcquistatoOld))
			$contentTpl->assign('isset',$giocatoreAcquistatoOld);
	}
	else
		$message->error('Hai raggiunto il limite di trasferimenti');
}
?>
