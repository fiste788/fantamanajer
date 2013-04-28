<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class ClubController extends ApplicationController {

    public function index() {
        $this->templates['content']->assign('elencoClub',Models\Club::getList());
    }

    public function show() {
        if(($dettaglioClub = Models\View\ClubStatistiche::getById($this->route['params']['id'])) == FALSE)
            Request::send404();

        $elencoClub = Models\Club::getList();

        $this->quickLinks->set('id',$elencoClub,"");
        $giocatori = Models\View\GiocatoreStatistiche::getByField('idClub',$dettaglioClub->id);
        $this->templates['content']->assign('giocatori',$giocatori);
        $this->templates['content']->assign('clubDett',$dettaglioClub);
        $this->templates['operation']->assign('elencoClub',$elencoClub);
    }
}

?>