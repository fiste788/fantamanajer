<?php
class GiornataTable extends DbTable
{
	const TABLE_NAME = "giornata";

	var $id;
	var $dataInizio;
	var $dataFine;
	
	function __construct()
	{
		$this->id = $this->getId();
		$this->dataInizio = $this->dataInizio();
		$this->dataFine = $this->dataFine();
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
	 * Setter: dataInizio
	 * @param String $dataInizio
	 * @return void
	 */
	public function setDataInizio( $dataInizio )
	{
		$this->dataInizio = $dataInizio;
	}

	/**
	 * Setter: dataFine
	 * @param String $dataFine
	 * @return void
	 */
	public function setDataFine( $dataFine )
	{
		$this->dataFine = $dataFine;
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
	 * Getter: dataInizio
	 * @return String
	 */
	public function getDataInizio()
	{
	 	return $this->dataInizio;
	}

	/**
	 * Getter: dataFine
	 * @return String
	 */
	public function getDataFine()
	{
	 	return $this->dataFine;
	}

    /**
	 * Getter: id
	 * @return Articolo[]
	 */
	public function getArticoli()
	{
	    require_once(INCDIR . 'articolo.db.inc.php');
	    if(empty($this->articoli))
			$this->articoli = Articolo::getByField('idGiornata',$this->getId());
		return $this->articoli;
	}
}
?>
