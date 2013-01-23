<?php

namespace Fantamanajer\Controllers;

class ArticoloController extends ApplicationController {

    public function index() {
        $this->templates['contentTpl']->assign('articoli', \Fantamanajer\Models\Articolo::getList());
    }

    public function build() {
        $this->templates['contentTpl']->assign('articolo', new \Fantamanajer\Models\Articolo());
    }

    public function create() {
        try {
            $articolo = new \Fantamanajer\Models\Articolo();
            $articolo->setIdUtente($_SESSION['idUtente']);
            $articolo->setIdGiornata(GIORNATA);
            $articolo->setIdLega($_SESSION['idLega']);
            $articolo->setDataCreazione('now');
            $articolo->save();
            $this->redirectTo("articoli");
        } catch(\Fantamanajer\FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("build");
        }

    }

    public function edit() {
        $articolo = \Fantamanajer\Models\Articolo::getById($this->route['params']['id']);
        \FirePHP::getInstance()->log($articolo);
        if(($articolo) == FALSE)
        	\Fantamanajer\Request::send404();
        $this->templates['contentTpl']->assign('articolo', $articolo);
    }

    public function update() {
        try {
            $articolo = \Fantamanajer\Models\Articolo::getById($this->route['params']['id']);
            $articolo->save();
            $this->redirectTo("articoli");
        } catch(\Fantamanajer\FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("edit");
        }
    }

    public function delete() {
        $articolo = \Fantamanajer\Models\Articolo::getById($this->route['params']['id']);
        $articolo->delete();
        $this->redirectTo("articoli");
    }
}

?>