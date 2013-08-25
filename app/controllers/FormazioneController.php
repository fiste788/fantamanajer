<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class FormazioneController extends ApplicationController {

    public function show() {
        $filterSquadra = $this->request->getParam('squadra', $_SESSION['idUtente']);
        $filterGiornata = $this->request->getParam('giornata', $this->currentGiornata->getId());
        
        $formazione = Models\Formazione::getLastFormazione($filterSquadra, $filterGiornata);
        $this->_showFormazione($formazione,$filterSquadra,$filterGiornata);
    }

    public function build() {
        $filterSquadra = $this->request->getParam('squadra', $_SESSION['idUtente']);
        $filterGiornata = $this->request->getParam('giornata', $this->currentGiornata->getId());
        
        $a = $this->request->getPostParams();
        if(empty($a)) {
            $formazione = Models\Formazione::getLastFormazione($filterSquadra, $filterGiornata);
        } else {
            $formazione = new Models\Formazione();
            $formazione->setIdUtente($_SESSION['idUtente']);
            $formazione->setIdGiornata($this->currentGiornata->id);
            $formazione->setModulo($formazione->calcModulo($this->request->getParam('titolari')));
            $formazione->giocatori = $formazione->getSchieramenti(array_merge($this->request->getParam('titolari'), $this->request->getParam('panchinari')));
        }
        
        $this->_showFormazione($formazione,$filterSquadra,$filterGiornata);
    }
    
    protected function _showFormazione(Models\Formazione $formazione,$squadra,$giornata) {
        $formazioniPresenti = Models\Formazione::getFormazioneByGiornataAndLega($giornata,$_SESSION['legaView']);
        $modulo = NULL;
        if ($formazione != NULL) {
            $modulo = explode('-',$formazione->getModulo());
            $modulo = array_combine(array("P","D","C","A"), array_map('intval', $modulo));
            if($formazione->getIdGiornata() != $this->currentGiornata->getId()) {
                $formazione = clone $formazione;
                $formazione->setIdGiornata($this->currentGiornata->getId());
                $formazione->setJolly(FALSE);
                $ids = array();
                foreach($formazione->giocatori as $giocatore) {
                    $ids[] = $giocatore->idGiocatore;
                }
                $giocatori = Models\View\GiocatoreStatistiche::getByIds($ids);
            } else {
                $giocatori = Models\View\GiocatoreStatistiche::getByField('idUtente',$squadra);
            }
        }
        $this->templates['content']->assign('usedJolly',Models\Formazione::usedJolly($squadra,$this->currentGiornata->getId()));
        $this->templates['content']->assign('modulo',$modulo);
        $this->templates['content']->assign('giocatori',$giocatori);
        $this->templates['content']->assign('formazione', $formazione);
        $this->templates['content']->assign('squadra',$squadra);
        $this->templates['content']->assign('giornata',$giornata);
        $this->templates['operation']->assign('squadre',  Models\Utente::getByField('idLega',$_SESSION['legaView']));
        $this->templates['operation']->assign('squadra',$squadra);
        $this->templates['operation']->assign('giornata',$giornata);
        $this->templates['operation']->assign('formazioniPresenti',$formazioniPresenti);
    }
    
    public function update() {
        try {
            $filterUtente = $this->request->getParam('utente', $_SESSION['idUtente']);
            $filterGiornata = $this->request->getParam('giornata', $this->currentGiornata->getId());
            if ($filterGiornata == $this->currentGiornata->id && $filterUtente == $_SESSION['idUtente']) {

                $titolari = $this->request->getParam('titolari');
                $panchinari = $this->request->getParam('panchinari');
                
                $formazione = new Models\Formazione();
                $formazioneOld = Models\Formazione::getFormazioneBySquadraAndGiornata($filterUtente, $filterGiornata);
                if($formazioneOld != FALSE) {
                    $formazione->setId($formazioneOld->getId());
                }
                
                $formazione->setIdGiornata($this->currentGiornata->id);
                $formazione->setIdUtente($_SESSION['idUtente']);
                $formazione->save(array('titolari' => $titolari, 'panchinari' => $panchinari));
                $this->setFlash(self::FLASH_SUCCESS,'Formazione caricata correttamente');
            }
        } catch(\Lib\FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
        }
        $this->renderAction('formazione');
    }

}

 