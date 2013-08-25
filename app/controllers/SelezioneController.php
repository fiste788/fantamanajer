<?php

namespace Fantamanajer\Controllers;

use \Fantamanajer\Models as Models;

class SelezioneController extends ApplicationController {

    public function update() {
        if (($selezione = Models\Selezione::getByField('idUtente', $_SESSION['idUtente'])) == FALSE) {
            $selezione = new Models\Selezione();
        }

        if ($_SESSION['logged']) {
            try {
                if ($this->request->getParam('submit') == 'Cancella acq.') {
                    Models\Selezione::unsetSelezioneByIdSquadra($_SESSION['idUtente']);
                    $this->setFlash(self::FLASH_SUCCESS, 'Cancellazione eseguita con successo');
                } else {
                    $selezione->setIdUtente($_SESSION['idUtente']);
                    $selezione->setIdLega($_SESSION['idLega']);
                    $selezione->save();
                    $this->setFlash(self::FLASH_SUCCESS,'Operazione eseguita con successo');
                }
            } catch (\Lib\FormException $e) {
                $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            }
            $this->redirectTo('trasferimento_index', array('id'=>$_SESSION['idUtente']));
        }   
    }
}

