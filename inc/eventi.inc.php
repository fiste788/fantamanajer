<?php
class eventi
{
	var $idEvento;
	var $idUtente;
	var $idLega;
	var $data;	//viene settata in automatico nel db con un on_update = CURRENT_TIMESTAMP
	var $tipo;	//1 = conferenza stampa, 2 = selezione giocatore, 3 = formazione, 4 = trasferimento, 
              //5=ingresso giocatore in lista, 6=uscita giocare dalla lista
	var $idExternal;	// id da cui prendere i dati dell'evento
	
	function addEvento($tipo,$idUtente,$idLega,$idExternal = NULL)
	{
		$q = "INSERT INTO eventi (idUtente,idLega,tipo,idExternal) 
				VALUES ('" . $idUtente . "','" . $idLega . "','" . $tipo . "','" . $idExternal . "')";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function deleteEventoByIdExternalAndTipo($idExternal,$tipo)
	{
		$q = "DELETE 
				FROM eventi WHERE idExternal = '" . $idExternal . "' AND tipo = '" . $tipo . "'";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function getEventi($idLega,$tipo = NULL,$min = 0,$max = 10)
	{
		$q = "SELECT eventi.idEvento,eventi.idUtente,data, date_format(data, '%a, %d %b %Y %H:%i:%s +0200') as pubData,tipo,idExternal,utente.nome 
				FROM eventi LEFT JOIN utente ON eventi.idUtente = utente.idUtente 
				WHERE eventi.idLega = '" . $idLega . "' OR eventi.idLega = '0'";
		if($tipo != NULL)
		  $q .= " AND tipo = '" . $tipo . "'";
		$q .= " ORDER BY data DESC 
				LIMIT " . $min . "," . $max . ";";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe))
			$values[] = $row;
		require_once(INCDIR.'articolo.inc.php');
		require_once(INCDIR.'trasferimenti.inc.php');
		require_once(INCDIR.'formazione.inc.php');
		require_once(INCDIR.'giocatore.inc.php');
		require_once(INCDIR.'links.inc.php');
		$formazioneObj = new formazione();
		$articoloObj = new articolo();
		$giocatoreObj = new giocatore();
		$trasferimentiObj = new trasferimenti();
		$linksObj = new links();
		if(isset($values))
		{
			foreach($values as $key => $val)
			{
				switch($val['tipo'])
				{
					case 1:
						$values[$key]['idExternal'] = $articoloObj->getArticoloById($val['idExternal']);
						$values[$key]['titolo'] = $val['nome'] . ' ha rilasciato una conferenza stampa intitolata '. $values[$key]['idExternal']['title'];
						$values[$key]['content'] = '';
						if(!empty($values[$key]['idExternal']['abstract'])) 
							$values[$key]['content'] = '<em>'.$values[$key]['idExternal']['abstract'].'</em><br />';
						$values[$key]['content'] .= $values[$key]['idExternal']['text'];
						$values[$key]['link'] = $linksObj->getLink('conferenzeStampa',array('giorn'=>$values[$key]['idExternal']['idGiornata']));break;
		    		case 2: $values[$key]['titolo'] = $val['nome'] . ' ha selezionato un giocatore per l\'acquisto';
									$values[$key]['content'] = ' ';break;
									$values[$key]['link'] = '';break;
					case 3: $values[$key]['idExternal'] = $formazioneObj->getFormazioneById($val['idExternal']);
                  $values[$key]['titolo'] = $val['nome'] . ' ha impostato la formazione per la giornata '. $values[$key]['idExternal']['idGiornata'];
									$titolari=$values[$key]['idExternal']['elenco'];
									$titolari=array_splice($titolari,0,11);
									$titolari = $giocatoreObj->getGiocatoriByArray($titolari);
									$values[$key]['content'] = 'Formazione: ';
									foreach($titolari as $key2=>$val2)
										$values[$key]['content'] .= $val2['cognome'].', ';
									$values[$key]['content'] = substr($values[$key]['content'],0,-2);
									$values[$key]['link'] = $linksObj->getLink('altreFormazioni',array('giorn'=>$values[$key]['idExternal']['idGiornata'],'squadra'=>$values[$key]['idExternal']['idSquadra']));break;
					case 4: $values[$key]['idExternal'] = $trasferimentiObj->getTrasferimentoById($val['idExternal']);
									$giocOld = $giocatoreObj->getGiocatoreById($values[$key]['idExternal']['idGiocOld']);
									$giocNew = $giocatoreObj->getGiocatoreById($values[$key]['idExternal']['idGiocNew']);
									$values[$key]['idExternal']['idGiocOld'] = $giocOld[$values[$key]['idExternal']['idGiocOld']];
									$values[$key]['idExternal']['idGiocNew'] = $giocNew[$values[$key]['idExternal']['idGiocNew']];
									$values[$key]['titolo'] = $val['nome'] . ' ha effettuato un trasferimento';
									$values[$key]['content'] = $val['nome'] .' ha ceduto il giocatore '. $values[$key]['idExternal']['idGiocOld']['nome'] .' ' . $values[$key]['idExternal']['idGiocOld']['cognome'].' e ha acquistato '. $values[$key]['idExternal']['idGiocNew']['nome'] .' ' . $values[$key]['idExternal']['idGiocNew']['cognome'];
									$values[$key]['link'] = $linksObj->getLink('trasferimenti',array('squad'=>$values[$key]['idExternal']['idSquadra']));
									unset($giocOld,$giocNew);break;
								case 5: 
									$player=$giocatoreObj->getGiocatoreById($values[$key]['idExternal']);
									$values[$key]['titolo'] =  $player[$values[$key]['idExternal']]['cognome'].' '.$player[$values[$key]['idExternal']]['nome'].' inserito nella lista giocatori';
									$values[$key]['content'] = ' ';
									$values[$key]['link'] = $linksObj->getLink('dettaglioGiocatore',array('id'=>$values[$key]['idExternal']));
									break;
								case 6: 
									$player=$giocatoreObj->getGiocatoreById($values[$key]['idExternal']);
									$values[$key]['titolo'] =  $player[$values[$key]['idExternal']]['cognome'].' '.$player[$values[$key]['idExternal']]['nome'].' non fa piu\' parte della lista giocatori';
									$values[$key]['content'] = ' ';
									$values[$key]['link'] = $linksObj->getLink('dettaglioGiocatore',array('id'=>$values[$key]['idExternal']));
									break;
				}
			}
			return $values;
		}
		else
			return FALSE;
	}
}
?>
