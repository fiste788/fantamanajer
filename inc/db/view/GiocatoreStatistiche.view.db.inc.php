<?php
require_once(TABLEDIR . 'Giocatore.table.db.inc.php');

class GiocatoreStatistiche extends GiocatoreTable
{
    const TABLE_NAME = "view_0_giocatoristatistiche";
	var $nomeClub;
	var $presenze;
	var $presenzeVoto;
	var $avgPunti;
	var $avgVoti;
	var $gol;
	var $golSubiti;
	var $assist;
	var $ammonizioni;
	var $espulsioni;
	var $quotazione;
	var $idUtente;
	
	function __construct() {
		$this->nomeClub = $this->getNomeClub();
		$this->presenze = $this->getPresenze();
		$this->presenzeVoto = $this->getPresenzeVoto();
		$this->avgPunti = $this->getAvgPunti();
		$this->avgVoti = $this->getAvgVoti();
		$this->gol = $this->getGol();
		$this->golSubiti = $this->getGolSubiti();
		$this->assist = $this->getAssist();
		$this->ammonizioni = $this->getAmmonizioni();
		$this->espulsioni = $this->getEspulsioni();
		$this->quotazione = $this->getQuotazione();
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
	 * Getter: presenze
	 * @return Int
	 */
	public function getPresenze()
	{
	 	return (int) $this->presenze;
	}

	/**
	 * Getter: presenzeVoto
	 * @return Int
	 */
	public function getPresenzeVoto()
	{
	 	return (int) $this->presenzeVoto;
	}

	/**
	 * Getter: avgPunti
	 * @return Double
	 */
	public function getAvgPunti()
	{
	 	return (double) $this->avgPunti;
	}

	/**
	 * Getter: avgVoti
	 * @return Double
	 */
	public function getAvgVoti()
	{
	 	return (double) $this->avgVoti;
	}

	/**
	 * Getter: gol
	 * @return Int
	 */
	public function getGol()
	{
	 	return (int) $this->gol;
	}

	/**
	 * Getter: golSubiti
	 * @return Int
	 */
	public function getGolSubiti()
	{
	 	return (int) $this->golSubiti;
	}

	/**
	 * Getter: assist
	 * @return Int
	 */
	public function getAssist()
	{
	 	return (int) $this->assist;
	}

	/**
	 * Getter: ammonizioni
	 * @return Int
	 */
	public function getAmmonizioni()
	{
	 	return (int) $this->ammonizioni;
	}

	/**
	 * Getter: espulsioni
	 * @return Int
	 */
	public function getEspulsioni()
	{
	 	return (int) $this->espulsioni;
	}

	/**
	 * Getter: quotazione
	 * @return Int
	 */
	public function getQuotazione()
	{
	 	return (int) $this->quotazione;
	}

	/**
	 * Getter: idUtente
	 * @return Int
	 */
	public function getIdUtente()
	{
	 	return (int) $this->idUtente;
	}
}
?>
