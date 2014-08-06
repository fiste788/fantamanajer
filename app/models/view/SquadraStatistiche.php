<?php

namespace Fantamanajer\Models\View;

class SquadraStatistiche extends \Fantamanajer\Models\Utente {

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
        parent::__construct();
        $this->totaleGol = is_null($this->totaleGol) ? NULL : $this->getTotaleGol();
        $this->totaleGolSubiti = is_null($this->totaleGolSubiti) ? NULL : $this->getTotaleGolSubiti();
        $this->totaleAssist = is_null($this->totaleAssist) ? NULL : $this->getTotaleAssist();
        $this->totaleAmmonizioni = is_null($this->totaleAmmonizioni) ? NULL : $this->getTotaleAmmonizioni();
        $this->totaleEspulsioni = is_null($this->totaleEspulsioni) ? NULL : $this->getTotaleEspulsioni();
        $this->avgPunti = is_null($this->avgPunti) ? NULL : $this->getAvgPunti();
        $this->avgVoti = is_null($this->avgVoti) ? NULL : $this->getAvgVoti();
        $this->punteggioMax = is_null($this->punteggioMax) ? NULL : $this->getPunteggioMax();
        $this->punteggioMin = is_null($this->punteggioMin) ? NULL : $this->getPunteggioMin();
        $this->punteggioMed = is_null($this->punteggioMed) ? NULL : $this->getPunteggioMed();
        $this->giornateVinte = is_null($this->giornateVinte) ? NULL : $this->getGiornateVinte();
    }

    /**
     * Getter: totaleGol
     * @return Int
     */
    public function getTotaleGol() {
        return (int) $this->totaleGol;
    }

    /**
     * Getter: totaleGolSubiti
     * @return Int
     */
    public function getTotaleGolSubiti() {
        return (int) $this->totaleGolSubiti;
    }

    /**
     * Getter: totaleAssist
     * @return Int
     */
    public function getTotaleAssist() {
        return (int) $this->totaleAssist;
    }

    /**
     * Getter: totaleAmmonizioni
     * @return Int
     */
    public function getTotaleAmmonizioni() {
        return (int) $this->totaleAmmonizioni;
    }

    /**
     * Getter: totaleEspulsioni
     * @return Int
     */
    public function getTotaleEspulsioni() {
        return (int) $this->totaleEspulsioni;
    }

    /**
     * Getter: avgPunti
     * @return Double
     */
    public function getAvgPunti() {
        return (double) $this->avgPunti;
    }

    /**
     * Getter: avgVoti
     * @return Double
     */
    public function getAvgVoti() {
        return (double) $this->avgVoti;
    }

    /**
     * Getter: punteggioMax
     * @return Double
     */
    public function getPunteggioMax() {
        return (double) $this->punteggioMax;
    }

    /**
     * Getter: punteggioMin
     * @return Double
     */
    public function getPunteggioMin() {
        return (double) $this->punteggioMin;
    }

    /**
     * Getter: punteggioMed
     * @return Double
     */
    public function getPunteggioMed() {
        return (double) $this->punteggioMed;
    }

    /**
     * Getter: giornateVinte
     * @return Int
     */
    public function getGiornateVinte() {
        return (int) $this->giornateVinte;
    }

}

