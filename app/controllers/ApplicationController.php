<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;
use \Fantamanajer\Lib as Lib;

abstract class ApplicationController extends \Lib\BaseController {

    /**
     *
     * @var \Fantamanajer\Lib\QuickLinks
     */
    protected $quickLinks;

    /**
     *
     * @var \Fantamanajer\Models\Giornata
     */
    protected $currentGiornata;

    /**
     *
     * @var \Fantamanajer\Models\Lega
     */
    protected $currentLega;

    /**
     *
     * @var \Fantamanajer\Lib\Ruolo[]
     */
    protected $ruoli = array();

    /**
     *
     * @var \Fantamanajer\Lib\Notify[]
     */
    protected $notifiche = array();

    public function __construct($controller, $action, $router, $route) {
        parent::__construct($controller, $action, $router, $route);
        $this->templates['operation'] = new \Savant3(array('template_path' => OPERATIONSDIR));
    }

    public function notAuthorized() {
        $this->setFlash(self::FLASH_NOTICE,"Non hai l'autorizzazione necessaria");
        $this->redirectTo('squadre');
    }

    public function initialize() {
        $this->ruoli['P'] = new Lib\Ruolo("Portiere", "Portieri", "POR");
        $this->ruoli['D'] = new Lib\Ruolo("Difensore", "Difensori", "DIF");
        $this->ruoli['C'] = new Lib\Ruolo("Centrocampista", "Centrocampisti", "CEN");
        $this->ruoli['A'] = new Lib\Ruolo("Attaccante", "Attaccanti", "ATT");

        $leghe = Models\Lega::getList();
        if (isset($_POST['legaView']))
            $_SESSION['legaView'] = $_POST['legaView'];
        if (isset($_SESSION['idLega']))
            $_SESSION['datiLega'] = $leghe[$_SESSION['idLega']];
        $this->currentGiornata = Models\Giornata::getCurrentGiornata();
        $this->currentLega = $leghe[$_SESSION['legaView']];
        foreach ($this->templates as $savant) {
            $savant->assign('ruoli', $this->ruoli);
            $savant->assign('dataFine', date_parse($this->currentGiornata->getData()->format("Y-m-d H:i:s")));
            $savant->assign('timestamp', $this->currentGiornata->getData()->getTimestamp());
            $savant->assign('currentGiornata',$this->currentGiornata->getId());
            $savant->assign('stagioneFinita',$this->currentGiornata->getStagioneFinita());
            $savant->assign('leghe', $leghe);
            $savant->assign('route',$this->route);
            $savant->assign('router', $this->router);
            $savant->assign('request',\Lib\Request::getInstance());
        }
        $this->quickLinks = new Lib\QuickLinks($this->request,$this->router,$this->route);
        $this->templates['navbar']->assign('entries',$this->pages);
        $this->initializeNotifiche();
        $this->templates['navbar']->assign('notifiche',$this->notifiche);
    }

    private function initializeNotifiche() {
         if(!$this->currentGiornata->getStagioneFinita()) {
            $formazione = Models\Formazione::getFormazioneBySquadraAndGiornata($_SESSION['idUtente'],$this->currentGiornata->getId());
            if(empty($formazione))
                $this->notifiche[] = new Lib\Notify(Lib\Notify::LEVEL_MEDIUM,'Non hai ancora impostato la formazione per questa giornata',$this->router->generate('formazione_edit'));
        }

        $giocatoriInattivi = Models\Giocatore::getGiocatoriInattiviByIdUtente($_SESSION['idUtente']);
        if(!empty($giocatoriInattivi) && count(Models\Trasferimento::getTrasferimentiByIdSquadra($_SESSION['idUtente'])) < $_SESSION['datiLega']->numTrasferimenti )
            $this->notifiche[] = new Lib\Notify(Lib\Notify::LEVEL_HIGH,'Un tuo giocatore non è più nella lista!',$this->router->generate('trasferimento_index'));
    }

    public function fetchOperationTpl() {
        $tpl = $this->controller . DS . $this->action . '.php';
        return file_exists(OPERATIONSDIR . $tpl) ? $this->templates['operation']->fetch($this->controller . DS . $this->action . '.php') : "";
    }

    public function render() {
        $this->templates['layout']->assign("quickLinks",$this->quickLinks);
        $this->fetched['operation'] = $this->fetchOperationTpl();
        parent::render();
    }

}

?>