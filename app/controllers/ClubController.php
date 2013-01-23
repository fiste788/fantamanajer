<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class ClubController extends ApplicationController {

    public function index() {
        $this->templates['contentTpl']->assign('elencoClub',Models\Club::getList());
    }

    public function show() {
        if(($dettaglioClub = Models\View\ClubStatistiche::getById($this->route['params']['id'])) == FALSE)
            Request::send404();

        $elencoClub = Models\Club::getList();

        $this->quickLinks->set('id',$elencoClub,"");
        $giocatori = Models\View\GiocatoreStatistiche::getByField('idClub',$dettaglioClub->id);
        $this->templates['contentTpl']->assign('giocatori',$giocatori);
        $this->templates['contentTpl']->assign('clubDett',$dettaglioClub);
        $this->templates['operationTpl']->assign('elencoClub',$elencoClub);
    }
}

?>