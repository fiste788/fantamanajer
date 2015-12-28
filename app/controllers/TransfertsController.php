<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Models as Models;
use Lib\FormException;

class TransfertsController extends ApplicationController {

    public function index() {
        $filterId = $this->request->getParam('team_id', $_SESSION['team']->id);
        $appo = Models\Transfert::getByField('team_id', $filterId);
        $transferts = !is_null($appo) ? (is_array($appo) ? $appo : array($appo)) : array();
       
        foreach ($transferts as $val) {
            $val->getOldMember();
            $val->getNewMember();
        }
        /*$playerFree = Models\Giocatore::getFreePlayer(NULL, $_SESSION['legaView']);

        //$trasferiti = Models\Giocatore::getGiocatoriInattiviByIdUtente($_SESSION['idUtente']);
        $selezione = Models\Selezione::getByField('idUtente', $_SESSION['idUtente']);
        if (empty($selezione)) {
            $selezione = new Models\Selezione();
        }
        if ($this->request->getParam('acquista') != NULL) {
            $selezione->setIdGiocatoreNew($this->request->getParam('acquista'));
        }
*/
        $this->noLayout = true;
        $team = Models\Team::getById($filterId);
        $this->templates['content']->assign('players', Models\View\MemberStats::getByTeam($team));
        //$this->templates['content']->assign('freePlayer', $playerFree);
        $this->templates['content']->assign('filterId', $filterId);
        $this->templates['content']->assign('transferts', $transferts);
        //$this->templates['content']->assign('selezione', $selezione);
        //$this->templates['operation']->assign('filterId', $filterId);
        //$this->templates['operation']->assign('elencoSquadre', Models\Utente::getByField('idLega', $_SESSION['legaView']));
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
            } catch (FormException $e) {
                $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            }
            $this->templates['content']->assign('selezione', $selezione);
            $this->redirectTo('trasferimenti', array('id'=>$_SESSION['idUtente']));
        }
        
    }

}

