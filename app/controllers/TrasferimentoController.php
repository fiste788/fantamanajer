<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class TrasferimentoController extends ApplicationController {

    public function index() {
        $filterId = $this->route['params']['id'] || $_SESSION['idUtente'];
        $trasferimentiAppo = Models\Trasferimento::getByField('idUtente',$filterId);

        if(!is_array($trasferimentiAppo) && !is_null($trasferimentiAppo))
            $trasferimenti[] = $trasferimentiAppo;
        else
            $trasferimenti = $trasferimentiAppo;

        foreach($trasferimenti as $val) {
            $val->getGiocatoreOld();
            $val->getGiocatoreNew();
        }
        $playerFree = Models\Giocatore::getFreePlayer(NULL,$_SESSION['legaView']);

        //$trasferiti = Models\Giocatore::getGiocatoriInattiviByIdUtente($_SESSION['idUtente']);
        $selezione = Models\Selezione::getByField('idUtente',$_SESSION['idUtente']);
        if(empty($selezione))
            $selezione = new Selezione();
        if($this->request->has('acquista'))
            $selezione->setIdGiocatoreNew($this->request->get('acquista'));

        $this->templates['contentTpl']->assign('giocatoriSquadra',Models\View\GiocatoreStatistiche::getByField('idUtente',$filterId));
        $this->templates['contentTpl']->assign('freePlayer',$playerFree);
        $this->templates['contentTpl']->assign('filterId',$filterId);
        $this->templates['contentTpl']->assign('trasferimenti',$trasferimenti);
        $this->templates['contentTpl']->assign('selezione',$selezione);
        $this->templates['operationTpl']->assign('filterId',$filterId);
        $this->templates['operationTpl']->assign('elencoSquadre',Models\Utente::getByField('idLega',$_SESSION['legaView']));
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