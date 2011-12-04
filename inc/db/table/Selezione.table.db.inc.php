<?php
require_once(TABLEDIR . 'dbTable.inc.php');

class SelezioneTable extends DbTable {
    const TABLE_NAME = 'selezione';
    var $id;
    var $idLega;
	var $idUtente;
	var $idGiocatoreOld;
	var $idGiocatoreNew;
	var $numSelezioni;
	
    function __construct() {
        $this->id = $this->getId();
        $this->idLega = $this->getIdLega();
        $this->idUtente = $this->getIdUtente();
        $this->idGiocatoreOld = $this->getIdGiocatoreOld();
        $this->idGiocatoreNew = $this->getIdGiocatoreNew();
        $this->numSelezioni = $this->getNumSelezioni();
    }

    public function __toString() {
        return $this->id;
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
     * Setter: idLega
     * @param Int $idLega
     * @return void
     */
    public function setIdLega( $idLega )
    {
    	$this->idLega = (int) $idLega;
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
     * Setter: idGiocatoreOld
     * @param Int $idGiocatoreOld
     * @return void
     */
    public function setIdGiocatoreOld( $idGiocatoreOld )
    {
    	$this->idGiocatoreOld = (int) $idGiocatoreOld;
    }

    /**
     * Setter: idGiocatoreNew
     * @param Int $idGiocatoreNew
     * @return void
     */
    public function setIdGiocatoreNew( $idGiocatoreNew )
    {
    	$this->idGiocatoreNew = (int) $idGiocatoreNew;
    }

    /**
     * Setter: numSelezioni
     * @param Int $numSelezioni
     * @return void
     */
    public function setNumSelezioni( $numSelezioni )
    {
    	$this->numSelezioni = (int) $numSelezioni;
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
	 * Setter: giocatore
	 * @param Giocatore $giocatore
	 * @return void
	 */
	public function setGiocatoreOld( $giocatoreOld )
	{
	    $this->giocatoreOld = $giocatoreOld;
		$this->setIdGiocatoreOld($giocatoreOld->getId());
	}

	/**
	 * Setter: giocatore
	 * @param Giocatore $giocatore
	 * @return void
	 */
	public function setGiocatoreNew( $giocatoreNew )
	{
	    $this->giocatoreNew = $giocatoreNew;
		$this->setIdGiocatoreNew($giocatoreNew->getId());
	}

	/**
	 * Setter: utente
	 * @param Utente $utente
	 * @return void
	 */
	public function setUtente( $utente )
	{
	    $this->utente = $utente;
		$this->setIdUtente($utente->getId());
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
     * Getter: idLega
     * @return Int
     */
    public function getIdLega()
    {
     	return (int) $this->idLega;
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
     * Getter: idGiocatoreOld
     * @return Int
     */
    public function getIdGiocatoreOld()
    {
     	return (int) $this->idGiocatoreOld;
    }

    /**
     * Getter: idGiocatoreNew
     * @return Int
     */
    public function getIdGiocatoreNew()
    {
     	return (int) $this->idGiocatoreNew;
    }

    /**
     * Getter: numSelezioni
     * @return Int
     */
    public function getNumSelezioni()
    {
     	return (int) $this->numSelezioni;
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
	 * Getter: utente
	 * @return Utente
	 */
    public function getUtente() {
    	require_once(INCDBDIR . 'utente.db.inc.php');
        if(empty($this->utente))
			$this->utente = Utente::getById($this->getIdUtente());
		return $this->utente;
	}

    /**
	 * Getter: id
	 * @return Giocatore
	 */
	public function getGiocatoreNew()
	{
	    require_once(INCDBDIR . 'giocatore.db.inc.php');
	    if(empty($this->giocatoreNew))
			$this->giocatoreNew = Giocatore::getByField('idUtente',$this->getId());
		return $this->giocatoreNew;
	}

    /**
	 * Getter: id
	 * @return Giocatore
	 */
	public function getGiocatoreOld()
	{
	    require_once(INCDBDIR . 'giocatore.db.inc.php');
	    if(empty($this->giocatoreOld))
			$this->giocatoreOld = Giocatore::getByField('idUtente',$this->getId());
		return $this->giocatoreOld;
	}
}
?>
