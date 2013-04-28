<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class LegaController extends ApplicationController {

    public function edit() {
        $this->templates['content']->assign('lega', $_SESSION['datiLega']);
        $this->templates['content']->assign('default',Models\Lega::getDefaultValue());
    }

    public function update() {
        try {
            $lega = Models\Lega::getById($_SESSION['idLega']);
            $lega->save();
            if($lega->getId() == $_SESSION['idLega'])
                $_SESSION['datiLega'] = $lega;
            $this->setFlash(self::FLASH_SUCCESS, "Operazione effettuata correttamente");
            $this->redirectTo("impostazioni");
        } catch(\Lib\FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("edit");
        }
    }
}

?>