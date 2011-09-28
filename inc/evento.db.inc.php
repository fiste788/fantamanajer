<?php
class Evento extends DbTable
{
	var $idEvento;
	var $idUtente;
	var $idLega;
	var $data;	//viene settata in automatico nel db con un on_update = CURRENT_TIMESTAMP
	var $tipo;	//1 = conferenza stampa, 2 = selezione giocatore, 3 = formazione, 4 = trasferimento, 5=ingresso giocatore in lista, 6=uscita giocare dalla lista
	var $idExternal;	// id da cui prendere i dati dell'evento
	
	function addEvento($tipo,$idUtente,$idLega,$idExternal = NULL)
	{
		$q = "INSERT INTO evento (idUtente,idLega,tipo,idExternal) 
				VALUES ('" . $idUtente . "','" . $idLega . "','" . $tipo . "','" . $idExternal . "')";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	function deleteEventoByIdExternalAndTipo($idExternal,$tipo)
	{
		$q = "DELETE 
				FROM evento WHERE idExternal = '" . $idExternal . "' AND tipo = '" . $tipo . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	function getEventi($idLega,$tipo = NULL,$min = 0,$max = 10)
	{
		require_once(INCDIR . 'articolo.db.inc.php');
		require_once(INCDIR . 'trasferimento.db.inc.php');
		require_once(INCDIR . 'formazione.db.inc.php');
		require_once(INCDIR . 'giocatore.db.inc.php');
		require_once(INCDIR . 'links.inc.php');
		
		$ruoli = array("articoli" =>
					array (
						'P'=> "il",
						'D'=> "il",
						'C'=> "il",
						'A'=> "l'"),
				"nome" =>
				array (
					'P'=> "portiere",
					'D'=> "difensore",
					'C'=> "centrocampista",
					'A'=> "attaccante"
		));
		$q = "SELECT evento.idEvento,evento.idUtente,data, date_format(data, '%a, %d %b %Y %H:%i:%s +0200') as pubData,tipo,idExternal,utente.nome 
				FROM evento LEFT JOIN utente ON evento.idUtente = utente.idUtente ";
		if($idLega != NULL)
			$q .= "WHERE (evento.idLega = '" . $idLega . "' OR evento.idLega = '0')";
		if($tipo != NULL)
		  $q .= " AND tipo = '" . $tipo . "'";
		$q .= " ORDER BY data DESC 
				LIMIT " . $min . "," . $max . ";";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[] = $row;
		if(isset($values))
		{
			foreach($values as $key => $val)
			{
				switch($val->tipo)
				{
					case 1:
						$values[$key]->idExternal = Articolo::getArticoloById($val->idExternal);
						$values[$key]->titolo = $val->nome . ' ha rilasciato una conferenza stampa intitolata '. $values[$key]->idExternal->title;
						$values[$key]->content = '';
						if(!empty($values[$key]->idExternal->abstract)) 
							$values[$key]->content = '<em>' . $values[$key]->idExternal->abstract . '</em><br />';
						$values[$key]->content .= $values[$key]->idExternal->text;
						$values[$key]->link = Links::getLink('conferenzeStampa',array('giorn'=>$values[$key]->idExternal->idGiornata));break;
					case 2: $values[$key]->titolo = $val->nome . ' ha selezionato un giocatore per l\'acquisto';
									$values[$key]->content = ' ';break;
									$values[$key]->link = '';break;
					case 3: $values[$key]->idExternal = Formazione::getFormazioneById($val->idExternal);
									$values[$key]->titolo = $val->nome . ' ha impostato la formazione per la giornata '. $values[$key]->idExternal->idGiornata;
									$titolari = $values[$key]->idExternal->elenco;
									$titolari = array_splice($titolari,0,11);
									$titolari = Giocatore::getGiocatoriByArray($titolari);
									$values[$key]->content = 'Formazione: ';
									foreach($titolari as $key2=>$val2)
										$values[$key]->content .= $val2->cognome.', ';
									$values[$key]->content = substr($values[$key]->content,0,-2);
									$values[$key]->link = Links::getLink('altreFormazioni',array('giorn'=>$values[$key]->idExternal->idGiornata,'squadra'=>$values[$key]->idExternal->idUtente));break;
					case 4: $values[$key]->idExternal = Trasferimento::getTrasferimentoById($val->idExternal);
									$giocOld = Giocatore::getGiocatoreById($values[$key]->idExternal->idGiocOld);
									$giocNew = Giocatore::getGiocatoreById($values[$key]->idExternal->idGiocNew);
									$values[$key]->idExternal->idGiocOld = $giocOld[$values[$key]->idExternal->idGiocOld];
									$values[$key]->idExternal->idGiocNew = $giocNew[$values[$key]->idExternal->idGiocNew];
									$values[$key]->titolo = $val->nome . ' ha effettuato un trasferimento';
									$values[$key]->content = $val->nome .' ha ceduto il giocatore '. $values[$key]->idExternal->idGiocOld->nome . ' ' . $values[$key]->idExternal->idGiocOld->cognome . ' e ha acquistato ' . $values[$key]->idExternal->idGiocNew->nome . ' ' . $values[$key]->idExternal->idGiocNew->cognome;
									$values[$key]->link = Links::getLink('trasferimenti',array('squadra'=>$values[$key]->idExternal->idUtente));
									unset($giocOld,$giocNew);break;
								case 5: 
									$player = Giocatore::getGiocatoreById($values[$key]->idExternal);
									$selected = $player[$values[$key]->idExternal];
									$values[$key]->titolo =  $selected->nome . ' ' . $selected->cognome . ' (' . $selected->nomeClub . ') inserito nella lista giocatori';
									$values[$key]->content = ucwords($ruoli['articoli'][$selected->ruolo]) . ' ' . $ruoli['nome'][$selected->ruolo] . ' ' . $selected->nome . ' ' . $selected->cognome . ' ora fa parte della rosa ' . $selected->partitivo . ' ' . $selected->nomeClub . ', pertanto è stato inserito nella lista giocatori';
									$values[$key]->link = Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$values[$key]->idExternal));
									break;
								case 6: 
									$player = Giocatore::getGiocatoreById($values[$key]->idExternal);
									$selected = $player[$values[$key]->idExternal];
									$values[$key]->titolo =  $selected->nome . ' ' . $selected->cognome . ' (ex '.$selected->nomeClub.') non fa più parte della lista giocatori';
									$values[$key]->content = ucwords($ruoli['articoli'][$selected->ruolo]) . ' ' . $ruoli['nome'][$selected->ruolo] . ' ' . $selected->nome . ' ' . $selected->cognome . ' non è più un giocatore '.$selected->partitivo.' '.$selected->nomeClub;
									$values[$key]->link = Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$values[$key]->idExternal));
									break;
								case 7: 
									$player = Giocatore::getGiocatoreById($values[$key]->idExternal);
									$selected = $player[$values[$key]->idExternal];
									$values[$key]->titolo =  $selected->determinativo.' '.$selected->nomeClub. ' ha ingaggiato '.$selected->nome . ' ' . $selected->cognome;
									$values[$key]->content = '';
									$values[$key]->link = Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$values[$key]->idExternal));
									break;
				}
			}
			return $values;
		}
		else
			return FALSE ;
	}
}
?>
