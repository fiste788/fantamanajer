<?php
class eventi
{
	var $idEvento;
	var $idUtente;
	var $data;	//viene settata in automatico nel db con un on_update = CURRENT_TIMESTAMP
	var $tipo;	//1 = conferenza stampa, 2 = selezione giocatore, 3 = formazione, 4 = trasferimento
	var $idExternal;	// id da cui prendere i dati dell'evento
	
	function addEvento($tipo,$idUtente,$idExternal = NULL)
	{
		$q = "INSERT INTO eventi (idUtente,tipo,idExternal) 
				VALUES ('" . $idUtente . "','" . $tipo . "','" . $idExternal . "')";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function deleteEventoByIdExternalAndTipo($idExternal,$tipo)
	{
		$q = "DELETE 
				FROM eventi WHERE idExternal = '" . $idExternal . "' AND tipo = '" . $tipo . "'";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function getEventi($tipo = NULL,$min = 0,$max = 10)
	{
		$q = "SELECT eventi.idEvento,eventi.idUtente,data, date_format(data, '%a, %d %b %Y %H:%i:%s +0200') as pubData,tipo,idExternal,utente.nome 
				FROM eventi INNER JOIN utente ON eventi.idUtente = utente.idUtente 
				ORDER BY data DESC";
		if($tipo != NULL)
		  $q .= " WHERE tipo = '" . $tipo . "'";
		$q .= " LIMIT " . $min . "," . $max . ";";
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
					case 1: $values[$key]['idExternal'] = $articoloObj->getArticoloById($val['idExternal']);
									$values[$key]['titolo'] = $val['nome'] . ' ha rilasciato una conferenza stampa intitolata '. $values[$key]['idExternal']['title'];
									$values[$key]['content'] = '';
									if(!empty($values[$key]['idExternal']['abstract'])) $values[$key]['content'] = '<em>'.$values[$key]['idExternal']['abstract'].'</em><br />';
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
		          					$giocOld[] = $values[$key]['idExternal']['idGiocOld'];
		          					$giocNew[] = $values[$key]['idExternal']['idGiocNew'];
		          					$values[$key]['idExternal']['idGiocOld'] = $giocatoreObj->getGiocatoriByArray($giocOld);
		          					$values[$key]['idExternal']['idGiocNew'] = $giocatoreObj->getGiocatoriByArray($giocNew);
									$values[$key]['titolo'] = $val['nome'] . ' ha effettuato un trasferimento';
									$values[$key]['content'] = $val['nome'] .' ha ceduto il giocatore '. $values[$key]['idExternal']['idGiocOld'][$giocOld[0]]['nome'] .' ' . $values[$key]['idExternal']['idGiocOld'][$giocOld[0]]['cognome'].' e ha acquistato '. $values[$key]['idExternal']['idGiocNew'][$giocNew[0]]['nome'] .' ' . $values[$key]['idExternal']['idGiocNew'][$giocNew[0]]['cognome'];
									$values[$key]['link'] = $linksObj->getLink('trasferimenti',array('squad'=>$values[$key]['idExternal']['idSquadra']));
									unset($giocOld,$giocNew);break;
				}
			}
			return $values;
		}
		else
			return FALSE;
	}
}
?>