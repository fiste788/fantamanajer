<?php

namespace Fantamanajer\Controllers;

use \Fantamanajer\Models as Models;

class PageController extends ApplicationController {

    public function home() {
        $giornata = Models\Score::getGiornateWithPunt();
        $bestPlayer = NULL;
        $bestPlayers = NULL;

        if ($giornata > 0) {
            foreach ($this->ruoli as $ruolo => $val) {

                $bestPlayers[$ruolo] = Models\Member::getBestPlayerByGiornataAndRuolo($giornata, $ruolo);
                $bestPlayer[$ruolo] = array_shift($bestPlayers[$ruolo]);
            }
        }

        $articoli = Models\Article::getLastArticoli(1);
        /* if($articoli != FALSE)
          foreach ($articoli as $key => $val)
          $articoli[$key]->text = Models\Emoticon::replaceEmoticon($val->testo,EMOTICONSURL); */

        $eventi = Models\Event::getEventi(NULL, NULL, 0, 5);

        $this->templates['content']->assign('squadre', Models\Team::getByField('idLega', $_SESSION['legaView']));
        $this->templates['content']->assign('giornata', $giornata);
        $this->templates['content']->assign('bestPlayer', $bestPlayer);
        $this->templates['content']->assign('bestPlayers', $bestPlayers);
        $this->templates['content']->assign('articoli', $articoli);
        $this->templates['content']->assign('eventi', $eventi);
    }

    public function contatti() {
        
    }

    public function download() {
        if ($this->request->getParam('type') == 'csv') {
            $filesVoti = \Fantamanajer\Lib\FileSystem::getFileIntoFolder(VOTICSVDIR);
        } else {
            $filesVoti = \Fantamanajer\Lib\FileSystem::getFileIntoFolder(VOTIXMLDIR);
        }
        sort($filesVoti);
        
        $this->templates['content']->assign('filesVoti', $filesVoti);
    }

    public function buildDownload() {
        if ($this->request->getParam('giornata') != NULL && $this->request->getParam('type') != NULL) {
            $path = ($this->request->getParam('type') == 'csv') ? VOTICSVDIR : VOTIXMLDIR;
            if ($this->request->getParam('giornata') == "all") {
                $createZip = new \CreateZip();
                $path = $createZip->createZipFromDir($path, 'voti' . strtoupper($this->request->getParam('type')));
                $createZip->forceDownload($path, "voti" . strtoupper(Request::getInstance()->get('type')) . ".zip");
                @unlink($path);
            } else {
                header("Content-type: text/csv");
                header("Content-Disposition: attachment;filename=" . basename(Request::getInstance()->get('giornata')));
                header("Content-Transfer-Encoding: binary");
                header("Expires: 0");
                header("Pragma: no-cache");
                readfile($path . Request::getInstance()->get('giornata'));
            }
            die();
        }
    }

}

