<?php
require_once(TABLEDIR . 'dbTable.inc.php');

class SchieramentoTable extends DbTable {
    var $idFormazione;
	var $idGiocatore;
	var $posizione;
	var $considerato;	//0 = non ha giocato, 1 = giocato, 2 = capitano
	
	function __construct() {
		$this->idFormazione = $this->getIdFormazione();
		$this->idGiocatore = $this->getIdGiocatore();
		$this->posizione = $this->getPosizione();
		$this->considerato = $this->getConsiderato();
	}

    function __toString() {
        return $this->idGiocatore;
    }

	/**
	 * Setter: idFormazione
	 * @param Int $idFormazione
	 * @return void
	 */
	public function setIdFormazione( $idFormazione )
	{
		$this->idFormazione = (int) $idFormazione;
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
	 * Setter: posizione
	 * @param Int $posizione
	 * @return void
	 */
	public function setPosizione( $posizione )
	{
		$this->posizione = (int) $posizione;
	}

	/**
	 * Setter: considerato
	 * @param Int $considerato
	 * @return void
	 */
	public function setConsiderato( $considerato )
	{
		$this->considerato = (int) $considerato;
	}

    /**
	 * Setter: formazione
	 * @param Formazione $formazione
	 * @return void
	 */
	public function setFormazione( $formazione )
	{
	    $this->formazione = $formazione;
		$this->setIdFormazione($formazione->getId());
	}
	
	/**
	 * Setter: giocatore
	 * @param Giocatore $giocatore
	 * @return void
	 */
	public function setGiocatore( $giocatore )
	{
	    $this->giocatore = $giocatore;
		$this->setIdGiocatore($giocatore->getId());
	}

	/**
	 * Getter: idFormazione
	 * @return Int
	 */
	public function getIdFormazione()
	{
	 	return (int) $this->idFormazione;
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
	 * Getter: posizione
	 * @return Int
	 */
	public function getPosizione()
	{
	 	return (int) $this->posizione;
	}

	/**
	 * Getter: considerato
	 * @return Int
	 */
	public function getConsiderato()
	{
	 	return (int) $this->considerato;
	}

    /**
	 * Getter: formazione
	 * @return Int
	 */
	public function getFormazione()
	{
		require_once(INCDBDIR . 'formazione.db.inc.php');
	    if(empty($this->formazione))
			$this->formazione = Formazione::getById($this->getIdFormazione());
		return $this->formazione;
	}
	
	/**
	 * Getter: giocatore
	 * @return Int
	 */
	public function getGiocatore()
	{
		require_once(INCDBDIR . 'giocatore.db.inc.php');
	    if(empty($this->giocatore))
			$this->giocatore = Giocatore::getById($this->getIdGiocatore());
		return $this->giocatore;
	}
}
?>
