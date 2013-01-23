<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class PunteggioController extends ApplicationController {

    public function index() {
        $filterGiornata = ($this->request->has('giornata')) ? $this->request->get('giornata') : $this->currentGiornata->id;

        $classificaDett = Models\Punteggio::getAllPunteggiByGiornata($filterGiornata,$_SESSION['legaView']);
        $squadre = $this->currentLega->getUtenti();

        $giornate = Models\Punteggio::getGiornateWithPunt();
        $this->templates['contentTpl']->assign('giornate',$giornate);
        $this->templates['contentTpl']->assign('classificaDett',$classificaDett);
        $this->templates['contentTpl']->assign('penalità',Models\Punteggio::getPenalitàByLega($_SESSION['legaView']));
        $this->templates['contentTpl']->assign('squadre',$squadre);
        $this->templates['contentTpl']->assign('posizioni',Models\Punteggio::getPosClassificaGiornata($_SESSION['legaView']));

        $this->templates['operationTpl']->assign('getGiornata',$filterGiornata);
        $this->templates['operationTpl']->assign('giornate',$giornate);
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

        $this->templates['contentTpl']->assign('somma',$utente->getPunteggioByGiornata($this->route['params']['idGiornata']));
        $this->templates['contentTpl']->assign('titolari',$titolari);
        $this->templates['contentTpl']->assign('panchinari',$dettaglio);
        $this->templates['contentTpl']->assign('penalità',Models\Punteggio::getPenalitàBySquadraAndGiornata($this->route['params']['idUtente'],$this->route['params']['idGiornata']));
        $this->templates['contentTpl']->assign('squadraDett',$utente);
        $this->templates['operationTpl']->assign('squadre',$this->currentLega->getUtenti());
        $this->templates['operationTpl']->assign('giornate',$giornate);
    }
}

?>