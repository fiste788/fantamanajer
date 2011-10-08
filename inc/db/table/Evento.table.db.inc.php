<?php
class EventoTable extends DbTable
{
	const TABLE_NAME = 'evento';
    var $id;
	var $idUtente;
	var $idLega;
	var $data;	//viene settata in automatico nel db con un on_update = CURRENT_TIMESTAMP
	var $tipo;	//1 = conferenza stampa, 2 = selezione giocatore, 3 = formazione, 4 = trasferimento, 5=ingresso giocatore in lista, 6=uscita giocare dalla lista
	var $idExternal;	// id da cui prendere i dati dell'evento
	
	function __construct()
	{
		$this->id = $this->getId();
		$this->idUtente = $this->getIdUtente();
		$this->idLega = $this->getIdLega();
		$this->data = $this->getData();
		$this->tipo = $this->getTipo();
		$this->idExternal = $this->getIdExternal();
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
	 * Setter: data
	 * @param String $data
	 * @return void
	 */
	public function setData( $data )
	{
		$this->data = $data;
	}

	/**
	 * Setter: tipo
	 * @param Int $tipo
	 * @return void
	 */
	public function setTipo( $tipo )
	{
		$this->tipo = (int) $tipo;
	}

	/**
	 * Setter: idExternal
	 * @param Int $idExternal
	 * @return void
	 */
	public function setIdExternal( $idExternal )
	{
		$this->idExternal = (int) $idExternal;
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
	 * Getter: id
	 * @return Int
	 */
	public function getId()
	{
	 	return (int) $this->id;
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

	/**
	 * Getter: data
	 * @return String
	 */
	public function getData()
	{
	 	return $this->data;
	}

	/**
	 * Getter: tipo
	 * @return Int
	 */
	public function getTipo()
	{
	 	return (int) $this->tipo;
	}

	/**
	 * Getter: idExternal
	 * @return Int
	 */
	public function getIdExternal()
	{
	 	return (int) $this->idExternal;
	}

    /**
	 * Getter: Utente
	 * @return Utente
	 */
	public function getLega()
	{
	    require_once(INCDIR . 'utente.db.inc.php');
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
	    require_once(INCDIR . 'giornata.db.inc.php');
	    if(empty($this->giornata))
			$this->giornata = Giornata::getById($this->getIdGiornata());
		return $this->giornata;
	}
}
?>
