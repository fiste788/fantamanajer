<?php
class eventi
{
	function addEvento($tipo,$idSquadra,$idExternal = NULL)
	{
		$q = "INSERT INTO eventi (idSquadra,tipo,idExternal) VALUES ('" . $idSquadra . "','" . $tipo . "','" . $idExternal . "');";
		mysql_query($q) or die(MYSQL_ERRNO().$q ." ".MYSQL_ERROR());
	}
	
	function getEventi($tipo = NULL,$min = 0,$max = 10)
	{
		$q = "SELECT eventi.*,squadra.nome FROM eventi INNER JOIN squadra ON eventi.idSquadra = squadra.idSquadra";
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
		$formazioneObj = new formazione();
		$articoloObj = new articolo();
		$giocatoreObj = new giocatore();
		foreach($values as $key=>$val)
		{
			switch($val['tipo'])
			{
				case 1: $values[$key]['idExternal'] = $articoloObj->getArticoloById($val['idExternal']);
								$values[$key]['titolo'] = $val['nome'] . ' ha rilasciato una conferenza stampa intitolata '. $values[$key]['idExternal']['title'];
								$values[$key]['content'] = '';
								if(!empty($values[$key]['idExternal']['abstract'])) $values[$key]['content'] = '<em>'.$values[$key]['idExternal']['abstract'].'</em><br />';
								$values[$key]['content'] .= $values[$key]['idExternal']['text'];
								$values[$key]['link'] = 'index.php?p=confStampa&giorn=' . $values[$key]['idExternal']['idGiornata'];break;
        case 2: $values[$key]['titolo'] = $val['nome'] . ' ha selezionato un giocatore per l\'acquisto';
								$values[$key]['content'] = '';break;
				case 3: $values[$key]['idExternal'] = $formazioneObj->getFormazioneById($val['idExternal']);
								$values[$key]['titolo'] = $val['nome'] . ' ha impostato la formazione per la giornata '. $values[$key]['idExternal']['IdGiornata'];
								$giocatori = explode('!',$values[$key]['idExternal']['Elenco']);
								$titolari = explode(';',$giocatori[0]);
								foreach($titolari as $key2=>$val2)
								  $titolari[$key2] = substr($val2,0,3);
								$titolari = $giocatoreObj->getGiocatoriByArray($titolari);
								echo "<pre>".print_r($titolari,1)."</pre>";
								$values[$key]['content'] = 'Formazione: ';
								foreach($titolari as $key2=>$val2)
								  $values[$key]['content'] .= $val2['Cognome'].',';
                $values[$key]['content'] = substr($values[$key]['content'],0,-1);
								
			}
		}
		echo "<pre>".print_r($values,1)."</pre>";
	}
}
?>
