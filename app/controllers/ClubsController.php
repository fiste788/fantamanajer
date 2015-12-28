<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class ClubsController extends ApplicationController {

    public function index() {
        $this->templates['content']->assign('clubs',Models\Club::getBySeason($this->currentSeason));
    }

    public function show() {
        if (($club = Models\View\ClubStats::getById($this->route['params']['id'])) == FALSE) {
            Request::send404();
        }

        $clubs = Models\Club::getList();

        $this->templates['header']->assign('title',$club->getName());
        $this->quickLinks->set('id',$clubs,"");
        $members = Models\View\MemberStats::getByField('club_id',$club->id);
        $this->templates['content']->assign('members',$members);
        $this->templates['content']->assign('club',$club);
        $this->templates['operation']->assign('clubs',$clubs);
    }
    
    public function probabiliFormazioni() {
        $clubs = Models\Club::getList();
        $newClub = array();
        foreach ($clubs as $club) {
            $newClub[strtolower($club->nome)] = $club->id;
        }

        $this->templates['content']->assign('elencoClub', $newClub);
    }
    
    public function probabiliFormazioni_html() {
        $url = "http://www.gazzetta.it/Calcio/prob_form/";
        echo utf8_encode(\Fantamanajer\Lib\FileSystem::contenutoCurl($url));
    }
}

 