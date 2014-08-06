<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Models\Formazione;
use Fantamanajer\Models\Giocatore;
use Fantamanajer\Models\Utente;
use Fantamanajer\Models\View\GiocatoreStatistiche;
use FirePHP;
use Lib\FormException;

class FormazioneController extends ApplicationController {

    public function show() {
        $filterSquadra = $this->request->getParam('squadra', $_SESSION['idUtente']);
        $filterGiornata = $this->request->getParam('giornata', $this->currentGiornata->getId());
        
        $formazione = Formazione::getLastFormazione($filterSquadra, $filterGiornata);
        $this->_showFormazione($filterSquadra,$filterGiornata,$formazione);
    }

    public function build() {
        $filterSquadra = $this->request->getParam('squadra', $_SESSION['idUtente']);
        $filterGiornata = $this->request->getParam('giornata', $this->currentGiornata->getId());
        
        $a = $this->request->getPostParams();
        if(empty($a)) {
            $formazione = Formazione::getLastFormazione($filterSquadra, $filterGiornata);
        } else {
            $formazione = new Formazione();
            $formazione->setIdUtente($_SESSION['idUtente']);
            $formazione->setIdGiornata($this->currentGiornata->id);
            $formazione->setModulo($formazione->calcModulo($this->request->getParam('titolari')));
            $formazione->giocatori = $formazione->getSchieramenti(array_merge($this->request->getParam('titolari'), $this->request->getParam('panchinari')));
        }
        
        $this->_showFormazione($filterSquadra,$filterGiornata,$formazione);
    }
    
    protected function _showFormazione($squadra,$giornata,Formazione $formazione = NULL) {
        $formazioniPresenti = Formazione::getFormazioneByGiornataAndLega($giornata,$_SESSION['legaView']);
        $modulo = NULL;
        if($giornata == $this->currentGiornata->id) {
            $giocatori = GiocatoreStatistiche::getByField('idUtente',$squadra);
        } else {
            $giocatori = Giocatore::getGiocatoriBySquadraAndGiornata($squadra,$giornata);
        }
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
            }
            if($giornata != $this->currentGiornata->id) {
                $giocatori = GiocatoreStatistiche::getByIds($ids);
            }
        }
        $giocatoriRuolo = array();
        foreach($giocatori as $giocatore) {
            $giocatoriRuolo[$giocatore->getRuolo()][$giocatore->getId()] = $giocatore;
        }
        $moduliConsentiti = array(
            "1-4-4-2"=>"4-4-2",
            "1-3-5-2"=>"3-5-2",
            "1-3-4-3"=>"3-4-3",
            "1-4-5-1"=>"4-5-1",
            "1-4-3-3"=>"4-3-3",
            "1-5-4-1"=>"5-4-1",
            "1-5-3-2"=>"5-3-2"
        );
        FirePHP::getInstance()->log($giornata);
        $this->templates['content']->assign('usedJolly',Formazione::usedJolly($squadra,$this->currentGiornata->getId()));
        $this->templates['content']->assign('modulo',$modulo);
        $this->templates['content']->assign('moduliConsentiti',$moduliConsentiti);
        $this->templates['content']->assign('giocatori',$giocatoriRuolo);
        $this->templates['content']->assign('formazione', $formazione);
        $this->templates['content']->assign('squadra',$squadra);
        $this->templates['content']->assign('giornata',$giornata);
        $this->templates['content']->assign('capitani',array("idCapitano","idVCapitano","idVVCapitano"));
        $this->templates['operation']->assign('squadre',  Utente::getByField('idLega',$_SESSION['legaView']));
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
                
                $formazione = new Formazione();
                //$formazione->setIdCapitano($this->request->getParam($name))
                FirePHP::getInstance()->log($formazione);
                $formazioneOld = Formazione::getFormazioneBySquadraAndGiornata($_SESSION['idUtente'], $this->currentGiornata->getId());
                if(!is_null($formazioneOld)) {
                    $formazione->setId($formazioneOld->getId());
                }
                
                $formazione->setIdGiornata($this->currentGiornata->id);
                $formazione->setIdUtente($_SESSION['idUtente']);
                $formazione->save(array('titolari' => $titolari, 'panchinari' => $panchinari));
                $this->setFlash(self::FLASH_SUCCESS,'Formazione caricata correttamente');
            }
        } catch(FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
        }
        $this->renderAction('formazione');
    }

}

 