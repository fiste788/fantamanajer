<?php
require_once(TABLEDIR . 'dbTable.inc.php');

class VotoTable extends DbTable
{
    const TABLE_NAME = "voto";
    var $id;
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
	
    function __construct() {
        $this->id = $this->getId();
        $this->idGiocatore = $this->getIdGiocatore();
        $this->idGiornata = $this->getIdGiornata();
        $this->valutato = $this->getValutato();
        $this->punti = $this->getPunti();
        $this->voto = $this->getVoto();
        $this->gol = $this->getGol();
        $this->golSubiti = $this->getGolSubiti();
        $this->golVittoria = $this->getGolVittoria();
        $this->golPareggio = $this->getGolPareggio();
        $this->assist = $this->getAssist();
        $this->ammonizioni = $this->getAmmonizioni();
        $this->espulsioni = $this->getEspulsioni();
        $this->rigoriSegnati = $this->getRigoriSegnati();
        $this->rigoriSubiti = $this->getRigoriSubiti();
        $this->presenza = $this->getPresenza();
        $this->titolare = $this->getTitolare();
        $this->quotazione = $this->getQuotazione();
    }

    public function __toString() {
		return $this->getId();
	}


    /**
	 * Setter: id
	 * @param Int $id
	 * @return void
	 */
	public function setId( $id )
	{
		$this->id = (int) $id;
	}

	/**
	 * Setter: idGiocatore
	 * @param Int $idGiocatore
	 * @return void
	 */
	public function setIdGiocatore( $idGiocatore )
	{
		$this->idGiocatore = (int) $idGiocatore;
	}

	/**
	 * Setter: idGiornata
	 * @param Int $idGiornata
	 * @return void
	 */
	public function setIdGiornata( $idGiornata )
	{
		$this->idGiornata = (int) $idGiornata;
	}

	/**
	 * Setter: valutato
	 * @param Boolean $valutato
	 * @return void
	 */
	public function setValutato( $valutato )
	{
		$this->valutato = (boolean) $valutato;
	}

	/**
	 * Setter: punti
	 * @param Float $punti
	 * @return void
	 */
	public function setPunti( $punti )
	{
		$this->punti = (float) $punti;
	}

	/**
	 * Setter: voto
	 * @param Float $voto
	 * @return void
	 */
	public function setVoto( $voto )
	{
		$this->voto = (float) $voto;
	}

	/**
	 * Setter: gol
	 * @param Int $gol
	 * @return void
	 */
	public function setGol( $gol )
	{
		$this->gol = (int) $gol;
	}

	/**
	 * Setter: golSubiti
	 * @param Int $golSubiti
	 * @return void
	 */
	public function setGolSubiti( $golSubiti )
	{
		$this->golSubiti = (int) $golSubiti;
	}

	/**
	 * Setter: golVittoria
	 * @param Int $golVittoria
	 * @return void
	 */
	public function setGolVittoria( $golVittoria )
	{
		$this->golVittoria = (int) $golVittoria;
	}

	/**
	 * Setter: golPareggio
	 * @param Int $golPareggio
	 * @return void
	 */
	public function setGolPareggio( $golPareggio )
	{
		$this->golPareggio = (int) $golPareggio;
	}

	/**
	 * Setter: assist
	 * @param Int $assist
	 * @return void
	 */
	public function setAssist( $assist )
	{
		$this->assist = (int) $assist;
	}

	/**
	 * Setter: ammonizioni
	 * @param Int $ammonizioni
	 * @return void
	 */
	public function setAmmonizioni( $ammonizioni )
	{
		$this->ammonizioni = (int) $ammonizioni;
	}

	/**
	 * Setter: espulsioni
	 * @param Int $espulsioni
	 * @return void
	 */
	public function setEspulsioni( $espulsioni )
	{
		$this->espulsioni = (int) $espulsioni;
	}

	/**
	 * Setter: rigoriSegnati
	 * @param Int $rigoriSegnati
	 * @return void
	 */
	public function setRigoriSegnati( $rigoriSegnati )
	{
		$this->rigoriSegnati = (int) $rigoriSegnati;
	}

	/**
	 * Setter: rigoriSubiti
	 * @param Int $rigoriSubiti
	 * @return void
	 */
	public function setRigoriSubiti( $rigoriSubiti )
	{
		$this->rigoriSubiti = (int) $rigoriSubiti;
	}

	/**
	 * Setter: presenza
	 * @param Boolean $presenza
	 * @return void
	 */
	public function setPresenza( $presenza )
	{
		$this->presenza = (boolean) $presenza;
	}

	/**
	 * Setter: titolare
	 * @param Boolean $titolare
	 * @return void
	 */
	public function setTitolare( $titolare )
	{
		$this->titolare = (boolean) $titolare;
	}

	/**
	 * Setter: quotazione
	 * @param Int $quotazione
	 * @return void
	 */
	public function setQuotazione( $quotazione )
	{
		$this->quotazione = (int) $quotazione;
	}

    /**
	 * Setter: giocatore
	 * @param Giocatore $giocatore
	 * @return void
	 */
	public function setGiocatore( $giocatore )
	{
	    $this->giocatore = $giocatore;
		$this->idGiocatore = $giocatore->getId();
	}

    /**
	 * Setter: giornata
	 * @param Giornata $giornata
	 * @return void
	 */
	public function setGiornata( $giornata )
	{
	    $this->giornata = $giornata;
		$this->idGiornata = $giornata->getId();
	}

    /**
	 * Getter: id
	 * @return Int
	 */
	public function getId()
	{
	 	return (int) $this->id;
	}

	/**
	 * Getter: idGiocatore
	 * @return Int
	 */
	public function getIdGiocatore()
	{
	 	return (int) $this->idGiocatore;
	}

	/**
	 * Getter: idGiornata
	 * @return Int
	 */
	public function getIdGiornata()
	{
	 	return (int) $this->idGiornata;
	}

	/**
	 * Getter: valutato
	 * @return Boolean
	 */
	public function getValutato()
	{
	 	return (boolean) $this->valutato;
	}

	/**
	 * Getter: punti
	 * @return Float
	 */
	public function getPunti()
	{
	 	return (float) $this->punti;
	}

	/**
	 * Getter: voto
	 * @return Float
	 */
	public function getVoto()
	{
	 	return (float) $this->voto;
	}

	/**
	 * Getter: gol
	 * @return Int
	 */
	public function getGol()
	{
	 	return (int) $this->gol;
	}

	/**
	 * Getter: golSubiti
	 * @return Int
	 */
	public function getGolSubiti()
	{
	 	return (int) $this->golSubiti;
	}

	/**
	 * Getter: golVittoria
	 * @return Int
	 */
	public function getGolVittoria()
	{
	 	return (int) $this->golVittoria;
	}

	/**
	 * Getter: golPareggio
	 * @return Int
	 */
	public function getGolPareggio()
	{
	 	return (int) $this->golPareggio;
	}

	/**
	 * Getter: assist
	 * @return Int
	 */
	public function getAssist()
	{
	 	return (int) $this->assist;
	}

	/**
	 * Getter: ammonizioni
	 * @return Int
	 */
	public function getAmmonizioni()
	{
	 	return (int) $this->ammonizioni;
	}

	/**
	 * Getter: espulsioni
	 * @return Int
	 */
	public function getEspulsioni()
	{
	 	return (int) $this->espulsioni;
	}

	/**
	 * Getter: rigoriSegnati
	 * @return Int
	 */
	public function getRigoriSegnati()
	{
	 	return (int) $this->rigoriSegnati;
	}

	/**
	 * Getter: rigoriSubiti
	 * @return Int
	 */
	public function getRigoriSubiti()
	{
	 	return (int) $this->rigoriSubiti;
	}

	/**
	 * Getter: presenza
	 * @return Boolean
	 */
	public function getPresenza()
	{
	 	return (boolean) $this->presenza;
	}

	/**
	 * Getter: titolare
	 * @return Boolean
	 */
	public function getTitolare()
	{
	 	return (boolean) $this->titolare;
	}

	/**
	 * Getter: quotazione
	 * @return Int
	 */
	public function getQuotazione()
	{
	 	return (int) $this->quotazione;
	}

    /**
	 * Getter: id
	 * @return Giocatore
	 */
	public function getGiocatore()
	{
	    require_once(INCDBDIR . 'GiocatoreStatisticheTable.db.inc.php');
	    if(empty($this->giocatore))
			$this->giocatore = GiocatoreStatistiche::getByField('id',$this->getIdGiocatore());
		return $this->giocatore;
	}

    /**
	 * Getter: Giornata
	 * @return Giornata
	 */
	public function getGiornata()
	{
	    require_once(INCDBDIR . 'giornata.db.inc.php');
	    if(empty($this->giornata))
			$this->giornata = Giornata::getById($this->getIdGiornata());
		return $this->giornata;
	}
}
?>
