<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class SquadraController extends ApplicationController {

    public function index() {
        $squadreAppo = Models\Utente::getByField('idLega',$_SESSION['legaView']);
        $classifica = Models\Punteggio::getClassificaByGiornata($_SESSION['legaView'],$this->currentGiornata->getId());
        $squadre = array();
        if($classifica != NULL) {
            foreach($classifica as $key => $val) {
                $squadre[$key] = $squadreAppo[$key];
                $squadre[$key]->giornateVinte = $val->giornateVinte;
            }
        } else
            $squadre = $squadreAppo;
        $this->templates['content']->assign('elencoSquadre',$squadre);
        $this->templates['content']->assign('ultimaGiornata',Models\Punteggio::getGiornateWithPunt());
    }
    
    public function index_json() {
        $squadreAppo = Models\Utente::getByIdLegaLite($_SESSION['legaView']);
        echo json_encode($squadreAppo);
    }

    public function show() {
        if(($squadraDett = Models\View\SquadraStatistiche::getById($this->request->getParam('id'))) == FALSE) {
            $this->send404();
        }

        $elencoSquadre = Models\Utente::getByField('idLega',$squadraDett->idLega);
        $this->quickLinks->set('id', $elencoSquadre, "");

        $this->templates['content']->assign('giocatori',Models\View\GiocatoreStatistiche::getByField('idUtente',$squadraDett->getId()));
        $this->templates['content']->assign('squadraDett',$squadraDett);
        $this->templates['operation']->assign('elencoSquadre',$elencoSquadre);
    }
    
    public function build() {
        $filterLega = $_SESSION['idLega'];
        $this->templates['content']->assign('squadre', Models\Utente::getByIdLegaLite($filterLega));
        $this->templates['content']->assign('portieri',  Models\Giocatore::getFreePlayer('P',$filterLega));
        $this->templates['content']->assign('difensori',  Models\Giocatore::getFreePlayer('D',$filterLega));
        $this->templates['content']->assign('attaccanti',  Models\Giocatore::getFreePlayer('A',$filterLega));
        $this->templates['content']->assign('centrocampisti',  Models\Giocatore::getFreePlayer('C',$filterLega));
    }
    
    public function create() {
        try {
            \FirePHP::getInstance()->log($this->request->getPostParams());
            $giocatori = $this->request->getParam('giocatore');
            $utente = $this->request->getParam('squadra');
            Models\Squadra::setSquadraGiocatoreByArray($_SESSION['idLega'], $giocatori, $utente);
            
            $this->redirectTo("crea_squadra");
        } catch(\Lib\FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("crea_squadra");
        }

    }

}

 