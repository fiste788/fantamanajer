<?php
class LegaTable extends DbTable
{
    const TABLE_NAME = "lega";
    
	var $id;
	var $nome;
	var $capitano;
	var $numTrasferimenti;
	var $numSelezioni;
	var $minFormazione;
	var $premi;
	var $punteggioFormazioneDimenticata;
	var $jolly;

    public function __construct() {
		$this->id = $this->getId();
		$this->nome = $this->getNome();
		$this->capitano = $this->getCapitano();
		$this->numTrasferimenti = $this->getNumTrasferimenti();
		$this->numSelezioni = $this->getNumSelezioni();
		$this->minFormazione = $this->getMinFormazione();
		$this->premi = $this->getPremi();
		$this->punteggioFormazioneDimenticata = $this->getPunteggioFormazioneDimenticata();
		$this->jolly = $this->getJolly();
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
	 * Setter: nome
	 * @param String $nome
	 * @return void
	 */
	public function setNome( $nome )
	{
		$this->nome = (string) $nome;
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
	 * Getter: nome
	 * @return String
	 */
	public function getNome()
	{
	 	return $this->nome;
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
	 * @return Utente[]
	 */
    public function getUtenti() {
    	require_once(INCDBDIR . 'utente.db.inc.php');
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
	
	public function __toString() {
		return $this->getNome();
	}
}
?>
