<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Models\Articolo;
use FirePHP;
use Lib\FormException;

class ArticoloController extends ApplicationController {

    public function index() {
        $giornata = $this->request->getParam('giornata',$this->currentGiornata->id);
        $articoli = $this->currentLega->getArticoliByGiornata($giornata);
        $giornate = Articolo::getGiornateArticoliExist($this->currentLega->id);
        array_push($giornate, $giornata);
        $this->templates['content']->assign('articoli', $articoli);
        $this->templates['operation']->assign('giornateWithArticoli', array_unique($giornate));
        $this->templates['operation']->assign('giornata', $giornata);
    }

    public function build() {
        $this->templates['content']->assign('articolo', new Articolo());
    }

    public function create() {
        try {
            $articolo = new Articolo();
            $articolo->setIdUtente($_SESSION['idUtente']);
            $articolo->setIdGiornata($this->currentGiornata->id);
            $articolo->setIdLega($this->currentLega->id);
            $articolo->setDataCreazione('now');
            $articolo->save();
            $this->redirectTo("articoli");
        } catch(FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("articolo_new");
        }

    }

    public function edit() {
        $articolo = Articolo::getById($this->route['params']['id']);
        FirePHP::getInstance()->log($articolo);
        if(($articolo) == FALSE)
            $this->send404();
        $this->templates['content']->assign('articolo', $articolo);
    }

    public function update() {
        try {
            $articolo = Articolo::getById($this->route['params']['id']);
            $articolo->save();
            $this->setFlash(self::FLASH_SUCCESS, "Modificato con successo");
            $this->redirectTo("articoli");
        } catch(FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("articolo_edit");
        }
    }

    public function delete() {
        $articolo = Articolo::getById($this->route['params']['id']);
        $articolo->delete();
        $this->redirectTo("articoli");
    }
}

 