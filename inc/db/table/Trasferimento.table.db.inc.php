<?php
require_once(TABLEDIR . 'dbTable.inc.php');

class TrasferimentoTable extends DbTable
{
	const TABLE_NAME = "trasferimento";
	
    var $id;
	var $idGiocatoreOld;
	var $idGiocatoreNew;
	var $idUtente;
	var $idGiornata;
	var $obbligato;
	
	function __construct() {
		$this->id = $this->getId();
		$this->idGiocatoreOld = $this->getIdGiocatoreOld();
		$this->idGiocatoreNew = $this->getIdGiocatoreNew();
		$this->idUtente = $this->getIdUtente();
		$this->idGiornata = $this->getIdGiornata();
		$this->obbligato = $this->getObbligato();
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
	 * Setter: idGiocatoreOld
	 * @param Int $idGiocatoreOld
	 * @return void
	 */
	public function setIdGiocatoreOld( $idGiocatoreOld )
	{
		$this->idGiocatoreOld = (int) $idGiocatoreOld;
	}

	/**
	 * Setter: idGiocatoreNew
	 * @param Int $idGiocatoreNew
	 * @return void
	 */
	public function setIdGiocatoreNew( $idGiocatoreNew )
	{
		$this->idGiocatoreNew = (int) $idGiocatoreNew;
	}

	/**
	 * Setter: idUtente
	 * @param Int $idUtente
	 * @return void
	 */
	public function setIdUtente( $idUtente )
	{
		$this->idUtente = (int) $idUtente;
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
	 * Setter: obbligato
	 * @param Boolean $obbligato
	 * @return void
	 */
	public function setObbligato( $obbligato )
	{
		$this->obbligato = (boolean) $obbligato;
	}
	
	/**
	 * Setter: giocatore
	 * @param Giocatore $giocatore
	 * @return void
	 */
	public function setGiocatoreOld( $giocatoreOld )
	{
	    $this->giocatoreOld = $giocatoreOld;
		$this->setIdGiocatoreOld($giocatoreOld->getId());
	}
	
	/**
	 * Setter: giocatore
	 * @param Giocatore $giocatore
	 * @return void
	 */
	public function setGiocatoreNew( $giocatoreNew )
	{
	    $this->giocatoreNew = $giocatoreNew;
		$this->setIdGiocatoreNew($giocatoreNew->getId());
	}
	
	/**
	 * Setter: utente
	 * @param Utente $utente
	 * @return void
	 */
	public function setUtente( $utente )
	{
	    $this->utente = $utente;
		$this->setIdUtente($utente->getId());
	}
	
	/**
	 * Setter: giornata
	 * @param Giornata $giornata
	 * @return void
	 */
	public function setGiornata( $giornata )
	{
	    $this->giornata = $giornata;
		$this->setIdGiornata($giornata->getId());
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
	 * Getter: idGiocatoreOld
	 * @return Int
	 */
	public function getIdGiocatoreOld()
	{
	 	return (int) $this->idGiocatoreOld;
	}

	/**
	 * Getter: idGiocatoreNew
	 * @return Int
	 */
	public function getIdGiocatoreNew()
	{
	 	return (int) $this->idGiocatoreNew;
	}

	/**
	 * Getter: idUtente
	 * @return Int
	 */
	public function getIdUtente()
	{
	 	return (int) $this->idUtente;
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
	 * Getter: obbligato
	 * @return Boolean
	 */
	public function getObbligato()
	{
	 	return (boolean) $this->obbligato;
	}
	
	/**
	 * Getter: giocatore
	 * @return Int
	 */
	public function getGiocatoreOld()
	{
		require_once(INCDBDIR . 'giocatore.db.inc.php');
	    if(empty($this->giocatoreOld))
			$this->giocatoreOld = Giocatore::getById($this->getIdGiocatoreOld());
		return $this->giocatoreOld;
	}
	
	/**
	 * Getter: giocatore
	 * @return Int
	 */
	public function getGiocatoreNew()
	{
		require_once(INCDBDIR . 'giocatore.db.inc.php');
	    if(empty($this->giocatoreNew))
			$this->giocatoreNew = Giocatore::getById($this->getIdGiocatoreNew());
		return $this->giocatoreNew;
	}
	
	/**
	 * Getter: utente
	 * @return Utente
	 */
    public function getUtente() {
    	require_once(INCDBDIR . 'utente.db.inc.php');
        if(empty($this->utente))
			$this->utente = Utente::getById($this->getIdUtente());
		return $this->utente;
	}
	
    /**
	 * Getter: giornata
	 * @return Giornata
	 */
    public function getGiornata() {
    	require_once(INCDBDIR . 'giornata.db.inc.php');
        if(empty($this->giornata))
			$this->giornata = Giornata::getById($this->getIdGiornata());
		return $this->giornata;
	}
	

	public function __toString() {
		return $this->getId();
	}
}
?>
