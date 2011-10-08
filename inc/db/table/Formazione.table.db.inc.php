<?php
class FormazioneTable extends DtTable
{
	const TABLE_NAME = 'formazione';
	var $id;
	var $idGiornata;
	var $idUtente;
	var $modulo;
	var $idC;
	var $idVC;
	var $idVVC;
	var $jolly;
	
	function __construct() {
		$this->id = $this->getId();
		$this->idGiornata = $this->getIdGiornata();
		$this->idUtente = $this->getIdUtente();
		$this->modulo = $this->getModulo();
		$this->idC = $this->getIdC();
		$this->idVC = $this->getIdVC();
		$this->idVVC = $this->getIdVVC();
		$this->jolly = $this->getJolly();
	}

	/**
	 * Setter: id
	 * @param Int $id
	 * @return void
	 */
	public function setId( $idFormazione )
	{
		$this->idFormazione = (int) $idFormazione;
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
	 * Setter: modulo
	 * @param String $modulo
	 * @return void
	 */
	public function setModulo( $modulo )
	{
		$this->modulo = $modulo;
	}

	/**
	 * Setter: idC
	 * @param Int $idC
	 * @return void
	 */
	public function setIdC( $idC )
	{
		$this->idC = (int) $idC;
	}

	/**
	 * Setter: idVC
	 * @param Int $idVC
	 * @return void
	 */
	public function setIdVC( $idVC )
	{
		$this->idVC = (int) $idVC;
	}

	/**
	 * Setter: idVVC
	 * @param Int $idVVC
	 * @return void
	 */
	public function setIdVVC( $idVVC )
	{
		$this->idVVC = (int) $idVVC;
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
	 * Getter: modulo
	 * @return String
	 */
	public function getModulo()
	{
	 	return $this->modulo;
	}

	/**
	 * Getter: idC
	 * @return Int
	 */
	public function getIdC()
	{
	 	return (int) $this->idC;
	}

	/**
	 * Getter: idVC
	 * @return Int
	 */
	public function getIdVC()
	{
	 	return (int) $this->idVC;
	}

	/**
	 * Getter: idVVC
	 * @return Int
	 */
	public function getIdVVC()
	{
	 	return (int) $this->idVVC;
	}

	/**
	 * Getter: jolly
	 * @return Boolean
	 */
	public function getJolly()
	{
	 	return (boolean) $this->jolly;
	}

}
?>
