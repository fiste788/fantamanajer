<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class PunteggioController extends ApplicationController {

    public function index() {
        $filterGiornata = ($this->request->has('giornata')) ? $this->request->get('giornata') : $this->currentGiornata->id;

        $classificaDett = Models\Punteggio::getAllPunteggiByGiornata($filterGiornata,$_SESSION['legaView']);
        $squadre = $this->currentLega->getUtenti();

        $giornate = Models\Punteggio::getGiornateWithPunt();
        $this->templates['content']->assign('giornate',$giornate);
        $this->templates['content']->assign('classificaDett',$classificaDett);
        $this->templates['content']->assign('penalità',Models\Punteggio::getPenalitàByLega($_SESSION['legaView']));
        $this->templates['content']->assign('squadre',$squadre);
        $this->templates['content']->assign('posizioni',Models\Punteggio::getPosClassificaGiornata($_SESSION['legaView']));

        $this->templates['operation']->assign('getGiornata',$filterGiornata);
        $this->templates['operation']->assign('giornate',$giornate);
    }

    public function show() {
        $dettaglio = Models\Giocatore::getVotiGiocatoriByGiornataAndSquadra($this->route['params']['idGiornata'],$this->route['params']['idUtente']);
        $formazione = Models\Formazione::getFormazioneBySquadraAndGiornata($this->route['params']['idUtente'],$this->route['params']['idGiornata']);
        if($dettaglio == FALSE && $formazione == FALSE)
            Lib\Request::send404();

        $utente = Models\Utente::getById($this->route['params']['idUtente']);
        $maxGiornate = Models\Punteggio::getGiornateWithPunt();
        for($i = 1;$i <= $maxGiornate;$i++)
            $giornate[$i] = $i;

        if($dettaglio != FALSE)
            $titolari = array_splice($dettaglio,0,11);
        else
            $titolari = FALSE;

        $this->quickLinks->set('idGiornata',$giornate,"",array('idUtente'=>$this->route['params']['idUtente']));

        $this->templates['content']->assign('somma',$utente->getPunteggioByGiornata($this->route['params']['idGiornata']));
        $this->templates['content']->assign('titolari',$titolari);
        $this->templates['content']->assign('panchinari',$dettaglio);
        $this->templates['content']->assign('penalità',Models\Punteggio::getPenalitàBySquadraAndGiornata($this->route['params']['idUtente'],$this->route['params']['idGiornata']));
        $this->templates['content']->assign('squadraDett',$utente);
        $this->templates['operation']->assign('squadre',$this->currentLega->getUtenti());
        $this->templates['operation']->assign('giornate',$giornate);
    }
}

?>