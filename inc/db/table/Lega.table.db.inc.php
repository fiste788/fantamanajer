<?php
class LegaTable extends DbTable
{
    const TABLE_NAME = "lega";
    
	var $id;
	var $nomeLega;
	var $capitano;
	var $numTrasferimenti;
	var $numSelezioni;
	var $minFormazione;
	var $premi;
	var $punteggioFormazioneDimenticata;
	var $jolly;
	var $utenti = Array();

    public function __construct() {
		$this->id = $this->getId();
		$this->nomeLega = $this->getNomeLega();
		$this->capitano = $this->getCapitano();
		$this->numTrasferimenti = $this->getNumTrasferimenti();
		$this->numSelezioni = $this->getNumSelezioni();
		$this->minFormazione = $this->getMinFormazione();
		$this->premi = $this->getPremi();
		$this->punteggioFormazioneDimenticata = $this->getPunteggioFormazioneDimenticata();
		$this->jolly = $this->getJolly();
		//$this->utenti = Utente::getUtentiByIdLega($this->getIdLega());
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
	 * Setter: nomeLega
	 * @param String $nomeLega
	 * @return void
	 */
	public function setNomeLega( $nomeLega )
	{
		$this->nomeLega = (string) $nomeLega;
	}

	/**
	 * Setter: capitano
	 * @param Boolean $capitano
	 * @return void
	 */
	public function setCapitano( $capitano )
	{
		$this->capitano = (boolean) $capitano;
	}

	/**
	 * Setter: numTrasferimenti
	 * @param Int $numTrasferimenti
	 * @return void
	 */
	public function setNumTrasferimenti( $numTrasferimenti )
	{
		$this->numTrasferimenti = (int) $numTrasferimenti;
	}

	/**
	 * Setter: numSelezioni
	 * @param Int $numSelezioni
	 * @return void
	 */
	public function setNumSelezioni( $numSelezioni )
	{
		$this->numSelezioni = (int) $numSelezioni;
	}

	/**
	 * Setter: minFormazione
	 * @param Int $minFormazione
	 * @return void
	 */
	public function setMinFormazione( $minFormazione )
	{
		$this->minFormazione = (int) $minFormazione;
	}

	/**
	 * Setter: premi
	 * @param String $premi
	 * @return void
	 */
	public function setPremi( $premi )
	{
		$this->premi = (string) $premi;
	}

	/**
	 * Setter: punteggioFormazioneDimenticata
	 * @param Int $punteggioFormazioneDimenticata
	 * @return void
	 */
	public function setPunteggioFormazioneDimenticata( $punteggioFormazioneDimenticata )
	{
		$this->punteggioFormazioneDimenticata = (int) $punteggioFormazioneDimenticata;
	}

	/**
	 * Setter: jolly
	 * @param Boolean $jolly
	 * @return void
	 */
	public function setJolly( $jolly )
	{
		$this->jolly = (boolean) $jolly;
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
	 * Getter: nomeLega
	 * @return String
	 */
	public function getNomeLega()
	{
	 	return (string) $this->nomeLega;
	}

	/**
	 * Getter: capitano
	 * @return Boolean
	 */
	public function getCapitano()
	{
	 	return (boolean) $this->capitano;
	}

	/**
	 * Getter: numTrasferimenti
	 * @return Int
	 */
	public function getNumTrasferimenti()
	{
	 	return (int) $this->numTrasferimenti;
	}

	/**
	 * Getter: numSelezioni
	 * @return Int
	 */
	public function getNumSelezioni()
	{
	 	return (int) $this->numSelezioni;
	}

	/**
	 * Getter: minFormazione
	 * @return Int
	 */
	public function getMinFormazione()
	{
	 	return (int) $this->minFormazione;
	}

	/**
	 * Getter: premi
	 * @return String
	 */
	public function getPremi()
	{
	 	return (string) $this->premi;
	}

	/**
	 * Getter: punteggioFormazioneDimenticata
	 * @return Int
	 */
	public function getPunteggioFormazioneDimenticata()
	{
	 	return (int) $this->punteggioFormazioneDimenticata;
	}

	/**
	 * Getter: jolly
	 * @return Boolean
	 */
	public function getJolly()
	{
	 	return (boolean) $this->jolly;
	}
	
	/**
	 * Getter: id
	 * @return Utenti[]
	 */
    public function getUtenti() {
        if(empty($this->utenti))
			$this->utenti = Utente::getByField('idLega',$this->getId());
		return $this->utenti;
	}
	
	/**
	 * Getter: id
	 * @return Articolo[]
	 */
	public function getArticoli()
	{
		require_once(INCDIR . 'articolo.db.inc.php');
		if(empty($this->articoli))
			$this->articoli = Articolo::getByField('idLega',$this->getId());
		return $this->articoli;
	}

    	/**
	 * Getter: id
	 * @return Evento[]
	 */
	public function getEventi()
	{
		require_once(INCDIR . 'eventi.db.inc.php');
		if(empty($this->eventi))
			$this->eventi = Evento::getByField('idLega',$this->getId());
		return $this->eventi;
	}
	
}
?>
