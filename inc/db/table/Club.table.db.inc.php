<?php
class ClubTable extends DbTable
{
	const TABLE_NAME = 'club';
	var $id;
	var $nomeClub;
	var $partitivo;
	var $determinativo;
	//var $giocatori = array();
	
	function __construct() {
		$this->id = $this->getId();
		$this->nomeClub = $this->getNomeClub();
		$this->partitivo = $this->getPartitivo();
		$this->determinativo = $this->getPartitivo();
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
	 * Setter: nomeClub
	 * @param String $nomeClub
	 * @return void
	 */
	public function setNomeClub( $nomeClub )
	{
		$this->nomeClub = $nomeClub;
	}

	/**
	 * Setter: partitivo
	 * @param String $partitivo
	 * @return void
	 */
	public function setPartitivo( $partitivo )
	{
		$this->partitivo = $partitivo;
	}

	/**
	 * Setter: determinativo
	 * @param String $determinativo
	 * @return void
	 */
	public function setDeterminativo( $determinativo )
	{
		$this->determinativo = $determinativo;
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
	 * Getter: nomeClub
	 * @return String
	 */
	public function getNomeClub()
	{
	 	return $this->nomeClub;
	}

	/**
	 * Getter: partitivo
	 * @return String
	 */
	public function getPartitivo()
	{
	 	return $this->partitivo;
	}

	/**
	 * Getter: determinativo
	 * @return String
	 */
	public function getDeterminativo()
	{
	 	return $this->determinativo;
	}
	
	/**
	 * Getter: giocatori
	 * @return Giocatore[]
	 */
	public function getGiocatori()
	{
	 	require_once(INCDIR . 'GiocatoreStatisticheTable.db.inc.php');
	    if(empty($this->giocatori))
			$this->giocatori = GiocatoreStatistiche::getByFields(array('idClub'=>$this->getId()));
		return $this->giocatori;
	}

}
?>
