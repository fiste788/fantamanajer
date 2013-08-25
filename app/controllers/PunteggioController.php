<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class PunteggioController extends ApplicationController {

    public function index() {
        $maxGiornate = Models\Punteggio::getGiornateWithPunt();
        $filterGiornata = $this->request->getParam('giornata',$maxGiornate);

        $this->request->setParam('giornata', $filterGiornata);
        \FirePHP::getInstance()->log($this->request->getParams());
        $classificaDett = Models\Punteggio::getAllPunteggiByGiornata($filterGiornata,$_SESSION['legaView']);
        $squadre = $this->currentLega->getUtenti();

        $giornate = array();
        for($i = 1;$i <= $maxGiornate;$i++)
            $giornate[$i] = $i;
        
        $this->quickLinks->set('giornata',$giornate,"");
        $this->templates['content']->assign('giornate',$giornate);
        $this->templates['content']->assign('classificaDett',$classificaDett);
        $this->templates['content']->assign('penalità',Models\Punteggio::getPenalitàByLega($_SESSION['legaView']));
        $this->templates['content']->assign('squadre',$squadre);
        $this->templates['content']->assign('posizioni',Models\Punteggio::getPosClassificaGiornata($_SESSION['legaView']));

        $this->templates['operation']->assign('getGiornata',$filterGiornata);
        $this->templates['operation']->assign('giornate',$maxGiornate);
    }

    public function show() {
        $filterGiornata = $this->request->getParam('giornata');
        $filterSquadra = $this->request->getParam('squadra');
        $dettaglio = Models\Giocatore::getVotiGiocatoriByGiornataAndSquadra($filterGiornata,$filterSquadra);
        $formazione = Models\Formazione::getFormazioneBySquadraAndGiornata($filterSquadra,$filterGiornata);
        /*if($dettaglio == FALSE && $formazione == FALSE)
            Lib\Request::send404();
*/
        $squadraDett = Models\View\SquadraStatistiche::getById($filterSquadra);
        $utente = Models\Utente::getById($filterSquadra);
        $maxGiornate = Models\Punteggio::getGiornateWithPunt();
        for($i = 1;$i <= $maxGiornate;$i++)
            $giornate[$i] = $i;

        if($dettaglio != FALSE)
            $titolari = array_splice($dettaglio,0,11);
        else
            $titolari = FALSE;

        $this->quickLinks->set('giornata',$giornate,"",array('squadra'=>$filterSquadra));

        $this->templates['content']->assign('media',$squadraDett->punteggioMed);
        $this->templates['content']->assign('somma',$utente->getPunteggioByGiornata($filterSquadra));
        $this->templates['content']->assign('titolari',$titolari);
        $this->templates['content']->assign('panchinari',$dettaglio);
        $this->templates['content']->assign('penalità',Models\Punteggio::getPenalitàBySquadraAndGiornata($filterSquadra,$filterGiornata));
        $this->templates['content']->assign('squadraDett',$utente);
        $this->templates['operation']->assign('squadre',$this->currentLega->getUtenti());
        $this->templates['operation']->assign('giornate',$giornate);
    }
}

 