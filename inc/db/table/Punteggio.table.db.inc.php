<?php
class PunteggioTable extends DbTable {

	const TABLE_NAME = 'punteggio';
	var $id;
	var $punteggio;
	var $penalità;
	var $idGiornata;
	var $idUtente;
	var $idLega;
	

	function __construct() {
		$this->id = $this->getId();
		$this->punteggio = $this->getPunteggio();
		$this->penalità = $this->getPenalità();
		$this->idGiornata = $this->getIdGiornata();
		$this->idUtente = $this->getIdUtente();
		$this->idLega = $this->getIdLega();
	}

    public function __toString() {
		return $this->getPunteggio();
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
	 * Setter: punteggio
	 * @param Int $punteggio
	 * @return void
	 */
	public function setPunteggio( $punteggio )
	{
		$this->punteggio = (int) $punteggio;
	}

	/**
	 * Setter: penalità
	 * @param String $penalità
	 * @return void
	 */
	public function setPenalità( $penalità )
	{
		$this->penalità = $penalità;
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
	 * Setter: idUtente
	 * @param Int $idUtente
	 * @return void
	 */
	public function setIdUtente( $idUtente )
	{
		$this->idUtente = (int) $idUtente;
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
	 * Getter: id
	 * @return Int
	 */
	public function getId()
	{
	 	return (int) $this->id;
	}

	/**
	 * Getter: punteggio
	 * @return Int
	 */
	public function getPunteggio()
	{
	 	return (int) $this->punteggio;
	}

	/**
	 * Getter: penalità
	 * @return String
	 */
	public function getPenalità()
	{
	 	return $this->penalità;
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
	 * Getter: idUtente
	 * @return Int
	 */
	public function getIdUtente()
	{
	 	return (int) $this->idUtente;
	}

	/**
	 * Getter: idLega
	 * @return Int
	 */
	public function getIdLega()
	{
	 	return (int) $this->idLega;
	}

}
?>
