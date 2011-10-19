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
		$this->dataInizio = $this->getDataInizio();
		$this->dataFine = $this->getDataFine();
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
	 * @param DateTime $dataInizio
	 * @return void
	 */
	public function setDataInizio( $dataInizio )
	{
	    if(is_a($this->dataInizio,"DateTime"))
	        $this->dataInizio = $dataInizio;
		else
	 		$this->dataInizio = new DateTime($dataInizio);
	}

	/**
	 * Setter: dataFine
	 * @param DateTime $dataFine
	 * @return void
	 */
	public function setDataFine( $dataFine )
	{
	    if(is_a($this->dataFine,"DateTime"))
	        $this->dataFine = $dataFine;
		else
	 		$this->dataFine = new DateTime($dataFine);
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
	    if(is_a($this->dataInizio,"DateTime"))
			return $this->dataInizio;
		else
	 		return new DateTime($this->dataInizio);
	}

	/**
	 * Getter: dataFine
	 * @return String
	 */
	public function getDataFine()
	{
	 	if(is_a($this->dataFine,"DateTime"))
			return $this->dataFine;
		else
	 		return new DateTime($this->dataFine);
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
