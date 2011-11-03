<?php
class GiocatoreTable extends DbTable
{
    const TABLE_NAME = "giocatore";
    var $id;
	var $nome;
	var $cognome;
	var $ruolo;
	var $idClub;
	var $status;
	
    function __construct()
	{
		$this->id = $this->getId();
		$this->nome = $this->getNome();
		$this->cognome = $this->getCognome();
		$this->ruolo = $this->getRuolo();
		$this->idClub = $this->getIdClub();
		$this->status = $this->getStatus();
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
		$this->nome = $nome;
	}

	/**
	 * Setter: cognome
	 * @param String $cognome
	 * @return void
	 */
	public function setCognome( $cognome )
	{
		$this->cognome = $cognome;
	}

	/**
	 * Setter: ruolo
	 * @param String $ruolo
	 * @return void
	 */
	public function setRuolo( $ruolo )
	{
		$this->ruolo = $ruolo;
	}

	/**
	 * Setter: idClub
	 * @param Int $idClub
	 * @return void
	 */
	public function setIdClub( $idClub )
	{
		$this->idClub = (int) $idClub;
	}

	/**
	 * Setter: status
	 * @param Boolean $status
	 * @return void
	 */
	public function setStatus( $status )
	{
		$this->status = (boolean) $status;
	}
	
	/**
	 * Setter: club
	 * @param Club $club
	 * @return void
	 */
	public function setClub( $club )
	{
	    $this->club = $club;
		$this->setIdClub = $club->getIdClub();
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
	 * Getter: cognome
	 * @return String
	 */
	public function getCognome()
	{
	 	return $this->cognome;
	}

	/**
	 * Getter: ruolo
	 * @return String
	 */
	public function getRuolo()
	{
	 	return $this->ruolo;
	}

	/**
	 * Getter: idClub
	 * @return Int
	 */
	public function getIdClub()
	{
	 	return (int) $this->idClub;
	}

	/**
	 * Getter: status
	 * @return Boolean
	 */
	public function getStatus()
	{
	 	return (boolean) $this->status;
	}

    /**
	 * Getter: club
	 * @return Club
	 */
	public function getClub()
	{
	    require_once(INCDIR . 'club.db.inc.php');
	    if(empty($this->club))
			$this->club = Club::getById($this->getIdClub());
		return $this->club;
	}
	

	public function __toString() {
		return $this->getCognome() . " " . $this->getNome();
	}
}
?>
