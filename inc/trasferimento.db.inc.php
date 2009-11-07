<?php 
class trasferimento
{
	var $idTrasf;
	var $idGiocOld;
	var $idGiocNew;
	var $idSquadra;
	var $idGiornata;
	var $obbligato;
	
	function getTrasferimentiByIdSquadra($idSquadra,$idGiornata = 0)
	{
		$q = "SELECT idGiocOld,t1.nome as nomeOld,t1.cognome as cognomeOld,idGiocNew,t2.nome as nomeNew,t2.cognome as cognomeNew, idGiornata, obbligato 
				FROM giocatore t1 INNER JOIN (trasferimento INNER JOIN giocatore t2 ON trasferimento.idGiocNew = t2.idGioc) ON t1.idGioc = trasferimento.idGiocOld 
				WHERE trasferimento.idSquadra = '" . $idSquadra . "' AND idGiornata > '" . $idGiornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		$values = array();
		while($row = mysql_fetch_assoc($exe))
			$values[] = $row;
		if(!empty($values))
			return $values;
		else
			return FALSE;
	}
	
	function transfer($giocOld,$giocNew,$squadra,$idLega)
	{
		require_once(INCDIR . 'squadre.inc.php');
		require_once(INCDIR . 'formazione.inc.php');
		require_once(INCDIR . 'schieramento.inc.php');
		require_once(INCDIR . 'eventi.inc.php');
		require_once(INCDIR . 'giocatore.inc.php');
		
		$squadreObj = new squadre();
		$formazioneObj = new formazione();
		$schieramentoObj = new schieramento();
		$eventiObj = new eventi();
		$giocatoreObj = new giocatore();
		
		$squadraOld = $squadreObj->getSquadraByIdGioc($giocNew,$idLega);
		mysql_query("START TRANSACTION");
		if($squadraOld == FALSE)
		{
			$q = "INSERT INTO squadra 
					VALUES ('" . $idLega . "','" . $squadra . "','". $giocNew . "')";
			$q2 = "DELETE 
					FROM squadra 
					WHERE idGioc = '". $giocOld . "' AND idLega = '" . $idLega . "'";
		}
		else
		{
			$q = "UPDATE squadra 
					SET idUtente = '" . $squadra . "' 
					WHERE idGioc = '". $giocNew . "' AND idLega = '" . $idLega . "'";
			$q2 = "UPDATE squadra 
					SET idUtente = '" . $squadraOld . "' 
					WHERE idGioc = '". $giocOld . "' AND idLega = '" . $idLega . "'";
		}
		mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			echo $q . "<br />";
		mysql_query($q2) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			echo $q2 . "<br />";
		$q = "INSERT INTO trasferimento (idGiocOld,idGiocNew,idSquadra,idGiornata,obbligato) 
				VALUES ('" . $giocOld . "' , '" . $giocNew . "' ,'" . $squadra . "','" . GIORNATA . "','" . $giocatoreObj->checkOutLista($giocOld) . "')";
		mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			echo $q . "<br />";
		$q = "SELECT idTrasf 
						FROM trasferimento
						WHERE idGiocOld = '" . $giocOld . "' AND idGiocNew = '" . $giocNew . "' AND idGiornata = '" . GIORNATA . "' AND idSquadra = '" . $squadra ."'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			echo $q . "<br />";
		$idTrasferimento = mysql_fetch_assoc($exe);
		$eventiObj->addEvento('4',$squadra,$idLega,$idTrasferimento['idTrasf']);
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
			$q = "INSERT INTO trasferimento (idGiocOld,idGiocNew,idSquadra,idGiornata,obbligato) 
					VALUES ('" . $giocNew . "' , '" . $giocOld . "' ,'" . $squadraOld . "','" . GIORNATA . "','" . $giocatoreObj->checkOutLista($giocNew) . "')";
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
			if(DEBUG)
				echo $q . "<br />";
			$q = "SELECT idTrasf 
						FROM trasferimento
						WHERE idGiocOld = '" . $giocNew . "' AND idGiocNew = '" . $giocOld . "' AND idGiornata = '" . GIORNATA . "' AND idSquadra = '" . $squadraOld ."'";
			$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
			if(DEBUG)
				echo $q . "<br />";
			$idTrasferimento = mysql_fetch_assoc($exe);
			$eventiObj->addEvento('4',$squadraOld,$idLega,$idTrasferimento['idTrasf']);
		}
		if(isset($err))
		{
			mysql_query("ROLLBACK");
			die("Errore nella transazione: <br />" . $err);
		}
		else
			mysql_query("COMMIT");
	}
	
	function doTransfertBySelezione()
	{
		require_once(INCDIR.'selezione.inc.php');
		require_once(INCDIR.'squadre.inc.php');
		require_once(INCDIR.'eventi.inc.php');
		require_once(INCDIR.'formazione.inc.php');
		require_once(INCDIR.'schieramento.inc.php');
		require_once(INCDIR.'giocatore.inc.php');
		
		$selezioneObj = new selezione();
		$squadreObj = new squadre();
		$eventiObj = new eventi();
		$formazioneObj = new formazione();
		$schieramentoObj = new schieramento();
		$giocatoreObj = new giocatore();
		
		$selezioni = $selezioneObj->getSelezioni();
		if($selezioni != FALSE)
		{
			foreach($selezioni as $key => $val)
			{
				mysql_query("START TRANSACTION");
				$squadreObj->unsetSquadraByIdGioc($val['giocOld'],$val['idLega']);
				$squadreObj->setSquadraByIdGioc($val['giocNew'],$val['idLega'],$val['idSquadra']);
				$q = "INSERT INTO trasferimento (idGiocOld,idGiocNew,idSquadra,idGiornata,obbligato) 
				VALUES ('" . $val['giocOld'] . "' , '" . $val['giocNew'] . "' ,'" . $val['idSquadra'] . "','" . GIORNATA . "','" . $giocatoreObj->checkOutLista($val['giocOld']) . "')";
				mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
				if(DEBUG)
				echo $q . "<br />";
				$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($val['idSquadra'],GIORNATA);
				if($formazione != FALSE)
				{
					if(in_array($val['giocOld'],$formazione['elenco']))
						$schieramentoObj->changeGioc($formazione['id'],$val['giocOld'],$val['giocNew']);
					if(in_array($val['giocOld'],$formazione['cap']))
						$formazioneObj->changeCap($formazione['id'],$val['giocNew'],array_search($val['giocOld'],$formazione['cap']));
				}
				$q = "SELECT idTrasf 
						FROM trasferimento
						WHERE idGiocOld = '" . $val['giocOld'] . "' AND idGiocNew = '" . $val['giocNew'] . "' AND idGiornata = '" . GIORNATA . "' AND idSquadra = '" . $val['idSquadra'] ."'";
				$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
				if(DEBUG)
					echo $q . "<br />";
				$idTrasferimento = mysql_fetch_assoc($exe);
				$eventiObj->addEvento('4',$val['idSquadra'],$val['idLega'],$idTrasferimento['idTrasf']);
				if(isset($err))
				{
					mysql_query("ROLLBACK");
					die("Errore nella transazione: <br />" . $err);
				}
				else
					mysql_query("COMMIT");
			}
			$selezioneObj->svuota();
		}
	}
	
	function getTrasferimentoById($id)
	{
		$q = "SELECT * 
				FROM trasferimento 
				WHERE idTrasf = '" . $id . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_assoc($exe))
			return $row;
	}
}
?>
