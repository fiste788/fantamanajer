<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Models as Models;
use Lib\FormException;

class SelectionController extends ApplicationController {

    public function update() {
        if ($this->request->isXmlHttpRequest()) {
            $this->response->setContentType("application/json");
        }
        if (($selection = Models\Selection::getByField('team_id', $_SESSION['team']->getId())) == FALSE) {
            $selection = new Models\Selection();
            $selection->setTeam($_SESSION['team']);
        }

        if ($_SESSION['logged']) {
            try {
                if ($this->request->getParam('submit') == 'Cancella acq.') {
                    $selection->setMemberNew(NULL);
                    $selection->setMemberOld(NULL);
                    if($selection->save()) {
                        echo '{"status":"' . self::FLASH_SUCCESS . '","message":"Cancellazione eseguita con successo"}';
                        //$this->setFlash(self::FLASH_SUCCESS, 'Cancellazione eseguita con successo');
                    }
                } else {
                    if($selection->new_member_id == NULL || $selection->old_member_id == NULL)
                        echo '{"status":"' . self::FLASH_NOTICE . '","message":"Selezionare i giocatori"}';
                    elseif($selection->save()) {
                        echo '{"status":"' . self::FLASH_SUCCESS . '","message":"Cancellazione eseguita con successo"}';
                        //$this->setFlash(self::FLASH_SUCCESS,'Operazione eseguita con successo');
                    }
                }
            } catch (FormException $e) {
                echo '{"status":"' . self::FLASH_NOTICE . '","message":"' . $e->getMessage() . '"}';
                //$this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            }
            $this->templates['content']->assign('selection', $selection);
            
            //$this->redirectTo('teams_show', array('id' => $_SESSION['team']->getId()));
        }
        
    }
}

