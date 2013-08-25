<?php

namespace Fantamanajer\Controllers;

use \Fantamanajer\Models as Models;

class EventoController extends ApplicationController {

    public function index() {
        $eventi = Models\Evento::getEventi($_SESSION['legaView'],$this->request->getParam('evento'),0,25);

        $this->templates['content']->assign('eventi',$eventi);
    }
}

