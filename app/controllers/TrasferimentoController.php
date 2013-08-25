<?php

namespace Fantamanajer\Controllers;

use \Fantamanajer\Models as Models;

class TrasferimentoController extends ApplicationController {

    public function index() {
        $filterId = $this->request->getParam('squadra', $_SESSION['idUtente']);
        $trasferimentiAppo = Models\Trasferimento::getByField('idUtente', $filterId);

        if (!is_array($trasferimentiAppo) && !is_null($trasferimentiAppo))
            $trasferimenti[] = $trasferimentiAppo;
        else
            $trasferimenti = $trasferimentiAppo;

        foreach ($trasferimenti as $val) {
            $val->getGiocatoreOld();
            $val->getGiocatoreNew();
        }
        $playerFree = Models\Giocatore::getFreePlayer(NULL, $_SESSION['legaView']);

        //$trasferiti = Models\Giocatore::getGiocatoriInattiviByIdUtente($_SESSION['idUtente']);
        $selezione = Models\Selezione::getByField('idUtente', $_SESSION['idUtente']);
        if (empty($selezione)) {
            $selezione = new Models\Selezione();
        }
        if ($this->request->getParam('acquista') != NULL) {
            $selezione->setIdGiocatoreNew($this->request->getParam('acquista'));
        }

        $this->templates['content']->assign('giocatoriSquadra', Models\View\GiocatoreStatistiche::getByField('idUtente', $filterId));
        $this->templates['content']->assign('freePlayer', $playerFree);
        $this->templates['content']->assign('filterId', $filterId);
        $this->templates['content']->assign('trasferimenti', $trasferimenti);
        $this->templates['content']->assign('selezione', $selezione);
        $this->templates['operation']->assign('filterId', $filterId);
        $this->templates['operation']->assign('elencoSquadre', Models\Utente::getByField('idLega', $_SESSION['legaView']));
    }

    public function selezione() {
        if (($selezione = Models\Selezione::getByField('idUtente', $_SESSION['idUtente'])) == FALSE) {
            $selezione = new Models\Selezione();
        }

        if ($_SESSION['logged']) {
            try {
                if ($this->request->getParam('submit') == 'Cancella acq.') {
                    Models\Selezione::unsetSelezioneByIdSquadra($_SESSION['idUtente']);
                    $this->setFlash(self::FLASH_SUCCESS, 'Cancellazione eseguita con successo');
                } else {
                    $selezione->setIdLega($_SESSION['idLega']);
                    $selezione->save();
                    $this->setFlash(self::FLASH_SUCCESS,'Operazione eseguita con successo');
                }
            } catch (\Lib\FormException $e) {
                $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            }
            $this->templates['content']->assign('selezione', $selezione);
            $this->redirectTo('trasferimenti', array('id'=>$_SESSION['idUtente']));
        }
        
    }

}

