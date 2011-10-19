<?php
class SquadraStatistiche extends Utente
{
	const TABLE_NAME = 'view_3_squadrastatistiche';
    var $totaleGol;
	var $totaleGolSubiti;
	var $totaleAssist;
	var $totaleAmmonizioni;
	var $totaleEspulsioni;
	var $avgPunti;
	var $avgVoti;
	var $punteggioMax;
	var $punteggioMin;
	var $punteggioMed;
	var $giornateVinte;
	
	function __construct() {
		$this->totaleGol = $this->getTotaleGol();
		$this->totaleGolSubiti = $this->getTotaleGolSubiti();
		$this->totaleAssist = $this->getTotaleAssist();
		$this->totaleAmmonizioni = $this->getTotaleAmmonizioni();
		$this->totaleEspulsioni = $this->getTotaleEspulsioni();
		$this->avgPunti = $this->getAvgPunti();
		$this->avgVoti = $this->getAvgVoti();
		$this->punteggioMax = $this->getPunteggioMax();
		$this->punteggioMin = $this->getPunteggioMin();
		$this->punteggioMed = $this->getPunteggioMed();
		$this->giornateVinte = $this->getGiornateVinte();
	}

	/**
	 * Getter: totaleGol
	 * @return Int
	 */
	public function getTotaleGol()
	{
	 	return (int) $this->totaleGol;
	}

	/**
	 * Getter: totaleGolSubiti
	 * @return Int
	 */
	public function getTotaleGolSubiti()
	{
	 	return (int) $this->totaleGolSubiti;
	}

	/**
	 * Getter: totaleAssist
	 * @return Int
	 */
	public function getTotaleAssist()
	{
	 	return (int) $this->totaleAssist;
	}

	/**
	 * Getter: totaleAmmonizioni
	 * @return Int
	 */
	public function getTotaleAmmonizioni()
	{
	 	return (int) $this->totaleAmmonizioni;
	}

	/**
	 * Getter: totaleEspulsioni
	 * @return Int
	 */
	public function getTotaleEspulsioni()
	{
	 	return (int) $this->totaleEspulsioni;
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
	 * Getter: punteggioMax
	 * @return Double
	 */
	public function getPunteggioMax()
	{
	 	return (double) $this->punteggioMax;
	}

	/**
	 * Getter: punteggioMin
	 * @return Double
	 */
	public function getPunteggioMin()
	{
	 	return (double) $this->punteggioMin;
	}

	/**
	 * Getter: punteggioMed
	 * @return Double
	 */
	public function getPunteggioMed()
	{
	 	return (double) $this->punteggioMed;
	}

	/**
	 * Getter: giornateVinte
	 * @return Int
	 */
	public function getGiornateVinte()
	{
	 	return (int) $this->giornateVinte;
	}

}
?>
