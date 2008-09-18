<?php
class eventi
{
	function addEvento($tipo,$idSquadra,$idExternal = NULL)
	{
		$q = "INSERT INTO eventi (idSquadra,tipo,idExternal) VALUES ('" . $idSquadra . "','" . $tipo . "','" . $idExternal . "');";
		mysql_query($q) or die(MYSQL_ERRNO().$q ." ".MYSQL_ERROR());
	}
	
	function deleteEventoByIdExternalAndTipo($idExternal,$tipo)
	{
		$q = "DELETE FROM eventi WHERE idExternal = '" . $idExternal . "' AND tipo = '" . $tipo . "';";
		mysql_query($q) or die(MYSQL_ERRNO().$q ." ".MYSQL_ERROR());
	}
	
	function getEventi($tipo = NULL,$min = 0,$max = 10)
	{
		$q = "SELECT eventi.idEvento,eventi.idSquadra,data, date_format(data, '%a, %d %b %Y %H:%i:%s +0200') as pubData,tipo,idExternal,squadra.nome FROM eventi INNER JOIN squadra ON eventi.idSquadra = squadra.idSquadra ORDER BY data DESC";
		if($tipo != NULL)
		  $q .= " WHERE tipo = '" . $tipo . "'";
		$q .= " LIMIT " . $min . "," . $max . ";";
		
		$exe = mysql_query($q) or die(MYSQL_ERRNO().$q ." ".MYSQL_ERROR());
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
			foreach($values as $key=>$val)
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
									$values[$key]['titolo'] = $val['nome'] . ' ha impostato la formazione per la giornata '. $values[$key]['idExternal']['IdGiornata'];
									$titolari=$values[$key]['idExternal']['Elenco'];
									$titolari=array_splice($titolari,0,11);
									$titolari = $giocatoreObj->getGiocatoriByArray($titolari);

									$values[$key]['content'] = 'Formazione: ';
									foreach($titolari as $key2=>$val2)
										$values[$key]['content'] .= $val2['Cognome'].', ';
		          					$values[$key]['content'] = substr($values[$key]['content'],0,-2);
		          					$values[$key]['link'] = $linksObj->getLink('altreFormazioni',array('squadra'=>$values[$key]['idExternal']['IdSquadra'],'giorn'=>$values[$key]['idExternal']['IdGiornata']));break;
		          	case 4: $values[$key]['idExternal'] = $trasferimentiObj->getTrasferimentoById($val['idExternal']);
		          					$giocOld[] = $values[$key]['idExternal']['IdGiocOld'];
		          					$giocNew[] = $values[$key]['idExternal']['IdGiocNew'];
		          					$values[$key]['idExternal']['IdGiocOld'] = $giocatoreObj->getGiocatoriByArray($giocOld);
		          					$values[$key]['idExternal']['IdGiocNew'] = $giocatoreObj->getGiocatoriByArray($giocNew);
									$values[$key]['titolo'] = $val['nome'] . ' ha effettuato un trasferimento';
									$values[$key]['content'] = $val['nome'] .' ha ceduto il giocatore '. $values[$key]['idExternal']['IdGiocOld'][$giocOld[0]]['Nome'] .' ' . $values[$key]['idExternal']['IdGiocOld'][$giocOld[0]]['Cognome'].' e ha acquistato '. $values[$key]['idExternal']['IdGiocNew'][$giocNew[0]]['Nome'] .' ' . $values[$key]['idExternal']['IdGiocNew'][$giocNew[0]]['Cognome'];
									$values[$key]['link'] = $linksObj->getLink('trasferimenti',array('squad'=>$values[$key]['idExternal']['IdSquadra']));
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
