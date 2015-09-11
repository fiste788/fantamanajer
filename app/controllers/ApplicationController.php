<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Lib\Notify;
use Fantamanajer\Lib\QuickLinks;
use Fantamanajer\Lib\Ruolo;
use Fantamanajer\Models\Formazione;
use Fantamanajer\Models\Giocatore;
use Fantamanajer\Models\Giornata;
use Fantamanajer\Models\Lega;
use Fantamanajer\Models\Trasferimento;
use FirePHP;
use Lib\BaseController;
use Lib\Request;
use Lib\Response;
use Savant3;

abstract class ApplicationController extends BaseController {

    /**
     *
     * @var QuickLinks
     */
    protected $quickLinks;

    /**
     *
     * @var Giornata
     */
    protected $currentGiornata;

    /**
     *
     * @var Lega
     */
    protected $currentLega;

    /**
     *
     * @var Ruolo[]
     */
    protected $ruoli = array();

    /**
     *
     * @var Notify[]
     */
    protected $notifiche = array();

    public function __construct(Request $request, Response $response) {
        parent::__construct($request,$response);
        FirePHP::getInstance()->setEnabled($_SESSION['roles'] == 2);
        $this->templates['operation'] = new Savant3(array('template_path' => OPERATIONSDIR));
        $response->setHeader("X-UA-Compatible", "IE=edge");
    }

    public function notAuthorized() {
        $this->setFlash(self::FLASH_NOTICE,"Non hai l'autorizzazione necessaria");
        $this->redirectTo('squadre');
    }

    public function initialize() {
        parent::initialize();
        $this->notifiche = array();
        $this->ruoli['P'] = new Ruolo("Portiere", "Portieri", "POR");
        $this->ruoli['D'] = new Ruolo("Difensore", "Difensori", "DIF");
        $this->ruoli['C'] = new Ruolo("Centrocampista", "Centrocampisti", "CEN");
        $this->ruoli['A'] = new Ruolo("Attaccante", "Attaccanti", "ATT");

        $leghe = Lega::getList();
        
        if (!is_null(Request::getRequest()->getParam('legaView',NULL))) {
            $_SESSION['legaView'] = Request::getRequest()->getParam('legaView');
        }
        if (isset($_SESSION['idLega'])) {
            $_SESSION['datiLega'] = $leghe[$_SESSION['idLega']];
        }
        $this->currentGiornata = Giornata::getCurrentGiornata();
        $this->currentLega = $leghe[$_SESSION['legaView']];
        $dataFine = Giornata::getTargetCountdown();
        foreach ($this->templates as $savant) {
            $savant->assign('ruoli', $this->ruoli);
            $savant->assign('dataFine', date_parse($dataFine->format("Y-m-d H:i:s")));
            $savant->assign('timestamp', $dataFine->getTimestamp());
            $savant->assign('currentGiornata',$this->currentGiornata->getId());
            $savant->assign('stagioneFinita',$this->currentGiornata->isStagioneFinita());
            $savant->assign('leghe', $leghe);
            $savant->assign('route',$this->route);
            $savant->assign('router', $this->router);
            $savant->assign('request',$this->request);
        }
        $this->quickLinks = new QuickLinks($this->request,$this->router,$this->route);
        $this->templates['navbar']->assign('entries',$this->pages);
        $this->initializeNotifiche();
        $this->templates['navbar']->assign('notifiche',$this->notifiche);
    }

    private function initializeNotifiche() {
         if(!$this->currentGiornata->isStagioneFinita()) {
            $formazione = Formazione::getFormazioneBySquadraAndGiornata($_SESSION['idUtente'],$this->currentGiornata->getId());
            if(empty($formazione)) {
                $this->notifiche[] = new Notify(Notify::LEVEL_MEDIUM,'Non hai ancora impostato la formazione per questa giornata',$this->router->generate('formazione'));
            }
        }

        $giocatoriInattivi = Giocatore::getGiocatoriInattiviByIdUtente($_SESSION['idUtente']);
        if(!empty($giocatoriInattivi) && count(Trasferimento::getTrasferimentiByIdSquadra($_SESSION['idUtente'])) < $_SESSION['datiLega']->numTrasferimenti ) {
            $this->notifiche[] = new Notify(Notify::LEVEL_HIGH,'Un tuo giocatore non è più nella lista!',$this->router->generate('trasferimento_index'));
        }
    }

    public function fetchOperationTpl() {
        $tpl = $this->controller . DS . $this->action . '.php';
        return file_exists(OPERATIONSDIR . $tpl) ? $this->templates['operation']->fetch($this->controller . DS . $this->action . '.php') : "";
    }

    public function render($content = NULL) {
        if(is_null($content)) {
            $this->templates['layout']->assign("quickLinks",$this->quickLinks);
            $this->fetched['operation'] = $this->fetchOperationTpl();
        }
        return parent::render($content);
    }

}

 