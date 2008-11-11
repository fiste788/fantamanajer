<?php 
class trasferimenti
{
	function getTrasferimentiByIdSquadra($idSquadra,$idGiornata = 0)
	{
		$q = "SELECT idGiocOld,t1.nome as nomeOld,t1.cognome as cognomeOld,idGiocNew,t2.nome as nomeNew,t2.cognome as cognomeNew, idGiornata 
				FROM giocatore t1 INNER JOIN (trasferimenti INNER JOIN giocatore t2 ON trasferimenti.idGiocNew = t2.idGioc) ON t1.idGioc = trasferimenti.idGiocOld 
				WHERE trasferimenti.idSquadra = '" . $idSquadra . "' AND idGiornata > '" . $idGiornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$values = array();
		while($row = mysql_fetch_array($exe))
			$values[] = $row;
		if(!empty($values))
			return $values;
		else
			return FALSE;
	}
	
	function transfer($giocOld,$giocNew,$squadra,$idLega)
	{
		require_once(INCDIR.'squadre.inc.php');
		require_once(INCDIR.'formazione.inc.php');
		require_once(INCDIR.'schieramento.inc.php');
		$squadreObj = new squadre();
		$formazioneObj = new formazione();
		$schieramentoObj = new schieramento();
		$squadraOld = $squadreObj->getSquadraByIdGioc($giocNew,$idLega);
		if($squadraOld == FALSE)
		{
			$q = "INSERT INTO squadre 
					VALUES ('" . $idLega . "','" . $squadra . "','". $giocNew . "')";
			$q2 = "DELETE 
					FROM squadre 
					WHERE idGioc = '". $giocOld . "' AND idLega = '" . $idLega . "'";
		}
		else
		{
			$q = "UPDATE squadre 
					SET idUtente = '" . $squadra . "' 
					WHERE idGioc = '". $giocNew . "' AND idLega = '" . $idLega . "'";
			$q2 = "UPDATE squadre 
					SET idUtente = '" . $squadraOld . "' 
					WHERE idGioc = '". $giocOld . "' AND idLega = '" . $idLega . "'";
		}
		$result = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);;
		$result = $result + mysql_query($q2) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$q = "INSERT INTO trasferimenti (idGiocOld,idGiocNew,idSquadra,idGiornata) 
				VALUES ('" . $giocOld . "' , '" . $giocNew . "' ,'" . $squadra . "','" . GIORNATA . "')";
		$result = $result + mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($squadra,GIORNATA);
		if($formazione != FALSE)
		{
			if(in_array($giocOld,$formazione['elenco']))
				$schieramentoObj->changeGioc($formazione['id'],$giocOld,$giocNew);
			if(in_array($giocOld,$formazione['cap']))
				$formazioneObj->changeCap($formazione['id'],$giocNew,array_search($giocOld,$formazione['cap']));
		}
		if($squadraOld != FALSE)
		{
			$formazioneOld = $formazioneObj->getFormazioneBySquadraAndGiornata($squadraOld,GIORNATA);
			if($formazioneOld != FALSE)
			{
				if(in_array($giocNew,$formazioneOld['elenco']))
					$schieramentoObj->changeGioc($formazioneOld['id'],$giocNew,$giocOld);
				if(in_array($giocNew,$formazioneOld['cap']))
					$formazioneObj->changeCap($formazioneOld['id'],$giocOld,array_search($giocNew,$formazioneOld['cap']));
			}
			$q = "INSERT INTO trasferimenti (idGiocOld,idGiocNew,idSquadra,idGiornata) 
					VALUES ('" . $giocNew . "' , '" . $giocOld . "' ,'" . $squadraOld . "','" . GIORNATA . "')";
			$result = $result + mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);;
		}
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	
	function doTransfertBySelezione()
	{
		require_once(INCDIR.'selezione.inc.php');
		require_once(INCDIR.'squadre.inc.php');
		$selezioneObj = new selezione();
		$squadreObj = new squadre();
		$selezioni = $selezioneObj->getSelezioni();
		if($selezioni != FALSE)
		{
			foreach($selezioni as $key => $val)
			{
				$squadreObj->unsetSquadraByIdGioc($val['giocOld'],$val['idLega']);
				$squadreObj->setSquadraByIdGioc($val['giocNew'],$val['idLega'],$val['idSquadra']);
				$q = "INSERT INTO trasferimenti (idGiocOld,idGiocNew,idSquadra,idGiornata) 
				VALUES ('" . $val['giocOld'] . "' , '" . $val['giocNew'] . "' ,'" . $val['idSquadra'] . "','" . GIORNATA . "')";
				mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
			}
			$selezioneObj->svuota();
		}
	}
	
	function getTrasferimentoById($id)
	{
		$q = "SELECT * 
				FROM trasferimenti 
				WHERE idTrasf = '" . $id . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe))
			return $row;
	}
}
?>