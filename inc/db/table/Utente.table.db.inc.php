<?php
class UtenteTable extends DbTable
{
	const TABLE_NAME = "utente";

    var $id;
	var $nome;
	var $cognome;
	var $nomeProp;
	var $mail;
	var $abilitaMail;
	var $username;
	var $password;
	var $amministratore;
	var $idLega;
	//var $lega;
//	var $articoli = Array();
	//var $giocatori = Array();
	
	function __construct()
	{
		$this->id = $this->getId();
		$this->nome = $this->getNome();
		$this->cognome = $this->getCognome();
		$this->nomeProp = $this->getNomeProp();
		$this->mail = $this->getMail();
		$this->abilitaMail = $this->getAbilitaMail();
		$this->username = $this->getUsername();
		$this->password = $this->getPassword();
		$this->amministratore = $this->getAmministratore();
		$this->idLega = $this->getIdLega();
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
	 * Setter: cognome
	 * @param String $cognome
	 * @return void
	 */
	public function setCognome( $cognome )
	{
		$this->cognome = (string) $cognome;
	}

	/**
	 * Setter: nomeProp
	 * @param String $nomeProp
	 * @return void
	 */
	public function setNomeProp( $nomeProp )
	{
		$this->nomeProp = (string) $nomeProp;
	}

	/**
	 * Setter: mail
	 * @param String $mail
	 * @return void
	 */
	public function setMail( $mail )
	{
		$this->mail = (string) $mail;
	}

	/**
	 * Setter: abilitaMail
	 * @param Boolean $abilitaMail
	 * @return void
	 */
	public function setAbilitaMail( $abilitaMail )
	{
		$this->abilitaMail = (boolean) $abilitaMail;
	}

	/**
	 * Setter: username
	 * @param String $username
	 * @return void
	 */
	public function setUsername( $username )
	{
		$this->username = (string) $username;
	}
	
	/**
	 * Setter: password
	 * @param String $password
	 * @return void
	 */
	public function setPassword( $password )
	{
		$this->password = (string) $password;
	}

	/**
	 * Setter: amministratore
	 * @param Int $amministratore
	 * @return void
	 */
	public function setAmministratore( $amministratore )
	{
		$this->amministratore = (int) $amministratore;
	}

	/**
	 * Setter: idLega
	 * @param Int $idLega
	 * @return void
	 */
	public function setIdLega( $id )
	{
		$this->idLega = (int) $idLega;
	}
	
	/**
	 * Setter: lega
	 * @param Lega $lega
	 * @return void
	 */
	public function setLega( $lega )
	{
	    $this->lega = $lega;
		$this->idLega = $lega->getIdLega();
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
	 	return (string) $this->nome;
	}

	/**
	 * Getter: cognome
	 * @return String
	 */
	public function getCognome()
	{
	 	return (string) $this->cognome;
	}

	/**
	 * Getter: nomeProp
	 * @return String
	 */
	public function getNomeProp()
	{
	 	return (string) $this->nomeProp;
	}

	/**
	 * Getter: mail
	 * @return String
	 */
	public function getMail()
	{
	 	return (string) $this->mail;
	}

	/**
	 * Getter: abilitaMail
	 * @return Boolean
	 */
	public function getAbilitaMail()
	{
	 	return (boolean) $this->abilitaMail;
	}

	/**
	 * Getter: username
	 * @return String
	 */
	public function getUsername()
	{
	 	return (string) $this->username;
	}
	
	/**
	 * Getter: password
	 * @return String
	 */
	public function getPassword()
	{
	 	return (string) $this->password;
	}

	/**
	 * Getter: amministratore
	 * @return Integer
	 */
	public function getAmministratore()
	{
	 	return (integer) $this->amministratore;
	}

	/**
	 * Getter: idLega
	 * @return Integer
	 */
	public function getIdLega()
	{
	 	return (int) $this->idLega;
	}

    /**
	 * Getter: Lega
	 * @return Lega
	 */
	public function getLega()
	{
	    require_once(INCDIR . 'lega.db.inc.php');
	    if(empty($this->lega))
			$this->lega = Lega::getById($this->getIdLega());
		return $this->lega;
	}
	
	/**
	 * Getter: id
	 * @return Articolo[]
	 */
	public function getArticoli()
	{
	    require_once(INCDBDIR . 'articolo.db.inc.php');
	    if(empty($this->articoli))
			$this->articoli = Articolo::getByField('idUtente',$this->getId());
		return $this->articoli;
	}
	
	/**
	 * Getter: id
	 * @return Giocatore[]
	 */
	public function getGiocatori()
	{
	    require_once(INCDBDIR . 'GiocatoreStatisticheTable.db.inc.php');
	    if(empty($this->giocatori))
			$this->giocatori = GiocatoreStatistiche::getByField('idUtente',$this->getId());
		return $this->giocatori;
	}
	
	/**
	 * Getter: id
	 * @return Evento[]
	 */
	public function getEventi()
	{
	    require_once(INCDBDIR . 'evento.db.inc.php');
	    if(empty($this->eventi))
			$this->eventi = Evento::getByField('idUtente',$this->getId());
		return $this->eventi;
	}
}
?>
