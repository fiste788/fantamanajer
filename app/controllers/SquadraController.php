<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class SquadraController extends ApplicationController {

    public function index() {
        $squadreAppo = Models\Utente::getByIdLegaLite($_SESSION['legaView']);
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
        if($this->format == '.json')
            $this->renderJson(json_encode($squadreAppo));
    }

    public function show() {
        if(($squadraDett = Models\View\SquadraStatistiche::getById($this->route['params']['id'])) == FALSE)
            \Lib\Request::send404();

        $elencoSquadre = Models\Utente::getByField('idLega',$squadraDett->idLega);
        $this->quickLinks->set('id', $elencoSquadre, "");

        $this->templates['content']->assign('giocatori',Models\View\GiocatoreStatistiche::getByField('idUtente',$squadraDett->getId()));
        $this->templates['content']->assign('squadraDett',$squadraDett);
        $this->templates['operation']->assign('elencoSquadre',$elencoSquadre);
    }

}

?>