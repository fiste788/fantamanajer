<?php
class ArticoloTable extends DbTable
{
	const TABLE_NAME = "articolo";
	var $id;
	var $title;
	var $abstract;
	var $text;
	var $insertDate;
	var $idUtente;
	var $idGiornata;
	var $idLega;
	
	function __construct()
	{
		$this->id = $this->getId();
		$this->title = $this->getTitle();
		$this->abstract = $this->getAbstract();
		$this->text = $this->getText();
		$this->insertDate = $this->getInsertDate();
		$this->idUtente = $this->getIdUtente();
		$this->idGiornata = $this->getIdGiornata();
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
	 * Setter: title
	 * @param String $title
	 * @return void
	 */
	public function setTitle( $title )
	{
		$this->title = $title;
	}

	/**
	 * Setter: abstract
	 * @param String $abstract
	 * @return void
	 */
	public function setAbstract( $abstract )
	{
		$this->abstract = $abstract;
	}

	/**
	 * Setter: text
	 * @param String $text
	 * @return void
	 */
	public function setText( $text )
	{
		$this->text = $text;
	}

	/**
	 * Setter: insertDate
	 * @param String $insertDate
	 * @return void
	 */
	public function setInsertDate( $insertDate )
	{
		$this->insertDate = $insertDate;
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
	 * Setter: idLega
	 * @param Int $idLega
	 * @return void
	 */
	public function setIdLega( $idLega )
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
		$this->setIdLega($lega->getIdLega());
	}
	
	/**
	 * Setter: utente
	 * @param Utente $utente
	 * @return void
	 */
	public function setUtente( $utente )
	{
	    $this->utente = $utente;
		$this->setIdUtente($lega->getIdUtente());
	}
	
	/**
	 * Setter: utente
	 * @param Giornata $giornata
	 * @return void
	 */
	public function setGiornata( $giornata )
	{
	    $this->giornata = $giornata;
		$this->setIdGiornata($lega->getIdGiornata());
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
	 * Getter: title
	 * @return String
	 */
	public function getTitle()
	{
	 	return $this->title;
	}

	/**
	 * Getter: abstract
	 * @return String
	 */
	public function getAbstract()
	{
	 	return $this->abstract;
	}

	/**
	 * Getter: text
	 * @return String
	 */
	public function getText()
	{
	 	return $this->text;
	}

	/**
	 * Getter: insertDate
	 * @return String
	 */
	public function getInsertDate()
	{
	 	return $this->insertDate;
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
	 * Getter: idLega
	 * @return Int
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
	 * Getter: Utente
	 * @return Utente
	 */
	public function getUtente()
	{
	    require_once(INCDBDIR . 'utente.db.inc.php');
	    if(empty($this->utente))
			$this->utente = Utente::getById($this->getIdUtente());
		return $this->utente;
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
