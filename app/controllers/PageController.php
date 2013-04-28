<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class PageController extends ApplicationController {

    public function home() {
        $giornata = Models\Punteggio::getGiornateWithPunt();
        $bestPlayer = NULL;
        $bestPlayers = NULL;

        if($giornata > 0) {
            foreach ($this->ruoli as $ruolo=>$val) {

                $bestPlayers[$ruolo] = Models\Giocatore::getBestPlayerByGiornataAndRuolo($giornata,$ruolo);
                $bestPlayer[$ruolo] = array_shift($bestPlayers[$ruolo]);
            }
        }

        $articoli = Models\Articolo::getLastArticoli(1);
        /*if($articoli != FALSE)
            foreach ($articoli as $key => $val)
                $articoli[$key]->text = Models\Emoticon::replaceEmoticon($val->testo,EMOTICONSURL);*/

        $eventi = Models\Evento::getEventi(NULL,NULL,0,5);

        $this->templates['content']->assign('squadre',Models\Utente::getByField('idLega',$_SESSION['legaView']));
        $this->templates['content']->assign('giornata',$giornata);
        $this->templates['content']->assign('bestPlayer',$bestPlayer);
        $this->templates['content']->assign('bestPlayers',$bestPlayers);
        $this->templates['content']->assign('articoli',$articoli);
        $this->templates['content']->assign('eventi',$eventi);
    }

    public function contatti() {

    }

}

?>