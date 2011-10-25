<?php
class VotoTable extends DbTable
{
    var $idGiocatore;
	var $idGiornata;
	var $valutato;
	var $punti;
	var $voto;
	var $gol;
	var $golSubiti;
	var $golVittoria;
	var $golPareggio;
	var $assist;
	var $ammonizioni;
	var $espulsioni;
	var $rigoriSegnati;
	var $rigoriSubiti;
	var $presenza;
	var $titolare;
	var $quotazione;
	


	/**
	 * Setter: idGiocatore
	 * @param String $idGiocatore
	 * @return void
	 */
	public function setIdGiocatore( $idGiocatore )
	{
		$this->idGiocatore = $idGiocatore;
	}

	/**
	 * Setter: idGiornata
	 * @param String $idGiornata
	 * @return void
	 */
	public function setIdGiornata( $idGiornata )
	{
		$this->idGiornata = $idGiornata;
	}

	/**
	 * Setter: valutato
	 * @param String $valutato
	 * @return void
	 */
	public function setValutato( $valutato )
	{
		$this->valutato = $valutato;
	}

	/**
	 * Setter: punti
	 * @param String $punti
	 * @return void
	 */
	public function setPunti( $punti )
	{
		$this->punti = $punti;
	}

	/**
	 * Setter: voto
	 * @param String $voto
	 * @return void
	 */
	public function setVoto( $voto )
	{
		$this->voto = $voto;
	}

	/**
	 * Setter: gol
	 * @param String $gol
	 * @return void
	 */
	public function setGol( $gol )
	{
		$this->gol = $gol;
	}

	/**
	 * Setter: golSubiti
	 * @param String $golSubiti
	 * @return void
	 */
	public function setGolSubiti( $golSubiti )
	{
		$this->golSubiti = $golSubiti;
	}

	/**
	 * Setter: golVittoria
	 * @param String $golVittoria
	 * @return void
	 */
	public function setGolVittoria( $golVittoria )
	{
		$this->golVittoria = $golVittoria;
	}

	/**
	 * Setter: golPareggio
	 * @param String $golPareggio
	 * @return void
	 */
	public function setGolPareggio( $golPareggio )
	{
		$this->golPareggio = $golPareggio;
	}

	/**
	 * Setter: assist
	 * @param String $assist
	 * @return void
	 */
	public function setAssist( $assist )
	{
		$this->assist = $assist;
	}

	/**
	 * Setter: ammonizioni
	 * @param String $ammonizioni
	 * @return void
	 */
	public function setAmmonizioni( $ammonizioni )
	{
		$this->ammonizioni = $ammonizioni;
	}

	/**
	 * Setter: espulsioni
	 * @param String $espulsioni
	 * @return void
	 */
	public function setEspulsioni( $espulsioni )
	{
		$this->espulsioni = $espulsioni;
	}

	/**
	 * Setter: rigoriSegnati
	 * @param String $rigoriSegnati
	 * @return void
	 */
	public function setRigoriSegnati( $rigoriSegnati )
	{
		$this->rigoriSegnati = $rigoriSegnati;
	}

	/**
	 * Setter: rigoriSubiti
	 * @param String $rigoriSubiti
	 * @return void
	 */
	public function setRigoriSubiti( $rigoriSubiti )
	{
		$this->rigoriSubiti = $rigoriSubiti;
	}

	/**
	 * Setter: presenza
	 * @param String $presenza
	 * @return void
	 */
	public function setPresenza( $presenza )
	{
		$this->presenza = $presenza;
	}

	/**
	 * Setter: titolare
	 * @param String $titolare
	 * @return void
	 */
	public function setTitolare( $titolare )
	{
		$this->titolare = $titolare;
	}

	/**
	 * Setter: quotazione
	 * @param String $quotazione
	 * @return void
	 */
	public function setQuotazione( $quotazione )
	{
		$this->quotazione = $quotazione;
	}

	/**
	 * Getter: idGiocatore
	 * @return String
	 */
	public function getIdGiocatore()
	{
	 	return $this->idGiocatore;
	}

	/**
	 * Getter: idGiornata
	 * @return String
	 */
	public function getIdGiornata()
	{
	 	return $this->idGiornata;
	}

	/**
	 * Getter: valutato
	 * @return String
	 */
	public function getValutato()
	{
	 	return $this->valutato;
	}

	/**
	 * Getter: punti
	 * @return String
	 */
	public function getPunti()
	{
	 	return $this->punti;
	}

	/**
	 * Getter: voto
	 * @return String
	 */
	public function getVoto()
	{
	 	return $this->voto;
	}

	/**
	 * Getter: gol
	 * @return String
	 */
	public function getGol()
	{
	 	return $this->gol;
	}

	/**
	 * Getter: golSubiti
	 * @return String
	 */
	public function getGolSubiti()
	{
	 	return $this->golSubiti;
	}

	/**
	 * Getter: golVittoria
	 * @return String
	 */
	public function getGolVittoria()
	{
	 	return $this->golVittoria;
	}

	/**
	 * Getter: golPareggio
	 * @return String
	 */
	public function getGolPareggio()
	{
	 	return $this->golPareggio;
	}

	/**
	 * Getter: assist
	 * @return String
	 */
	public function getAssist()
	{
	 	return $this->assist;
	}

	/**
	 * Getter: ammonizioni
	 * @return String
	 */
	public function getAmmonizioni()
	{
	 	return $this->ammonizioni;
	}

	/**
	 * Getter: espulsioni
	 * @return String
	 */
	public function getEspulsioni()
	{
	 	return $this->espulsioni;
	}

	/**
	 * Getter: rigoriSegnati
	 * @return String
	 */
	public function getRigoriSegnati()
	{
	 	return $this->rigoriSegnati;
	}

	/**
	 * Getter: rigoriSubiti
	 * @return String
	 */
	public function getRigoriSubiti()
	{
	 	return $this->rigoriSubiti;
	}

	/**
	 * Getter: presenza
	 * @return String
	 */
	public function getPresenza()
	{
	 	return $this->presenza;
	}

	/**
	 * Getter: titolare
	 * @return String
	 */
	public function getTitolare()
	{
	 	return $this->titolare;
	}

	/**
	 * Getter: quotazione
	 * @return String
	 */
	public function getQuotazione()
	{
	 	return $this->quotazione;
	}

	public function __toString() {
		return $this->getId();
	}

}
?>
