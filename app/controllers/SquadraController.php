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
        }else
            $squadre = $squadreAppo;
        $this->templates['contentTpl']->assign('elencoSquadre',$squadre);
        $this->templates['contentTpl']->assign('ultimaGiornata',Models\Punteggio::getGiornateWithPunt());
    }

    public function show() {
        if(($squadraDett = Models\View\SquadraStatistiche::getById($this->route['params']['id'])) == FALSE)
            \Lib\Request::send404();

        $elencoSquadre = Models\Utente::getByField('idLega',$squadraDett->idLega);
        $this->quickLinks->set('id', $elencoSquadre, "");

        $this->templates['contentTpl']->assign('giocatori',Models\View\GiocatoreStatistiche::getByField('idUtente',$squadraDett->getId()));
        $this->templates['contentTpl']->assign('squadraDett',$squadraDett);
        $this->templates['operationTpl']->assign('elencoSquadre',$elencoSquadre);
    }
}

?>