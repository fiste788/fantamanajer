<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Lib\Notify;
use Fantamanajer\Lib\QuickLinks;
use Fantamanajer\Models\League;
use Fantamanajer\Models\Lineup;
use Fantamanajer\Models\Matchday;
use Fantamanajer\Models\Member;
use Fantamanajer\Models\Season;
use Fantamanajer\Models\Transfert;
use Fantamanajer\Models\User;
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
     * @var Season 
     */
    protected $currentSeason;
    
    /**
     *
     * @var Matchday
     */
    protected $currentMatchday;

    /**
     *
     * @var League
     */
    protected $currentLeague;

    /**
     *
     * @var Notify[]
     */
    protected $notification = array();

    public function __construct(Request $request, Response $response) {
        parent::__construct($request,$response);
        FirePHP::getInstance()->setEnabled($_SESSION['roles'] == 2);
        FirePHP::getInstance()->setEnabled(true);
        $this->templates['operation'] = new Savant3(array('template_path' => OPERATIONSDIR));
        $response->setHeader("X-UA-Compatible", "IE=edge");
    }

    public function notAuthorized() {
        $this->setFlash(self::FLASH_NOTICE,"Non hai l'autorizzazione necessaria");
        $this->redirectTo('squadre');
    }

    public function initialize() {
        parent::initialize();
        $this->notification = array();
        $leagues = League::getList();
        
        if (!is_null(Request::getRequest()->getParam('league_view',NULL))) {
            $_SESSION['league_view'] = Request::getRequest()->getParam('league_view');
        }
        if (isset($_SESSION['league_id'])) {
            $_SESSION['league_data'] = $leagues[$_SESSION['league_data']];
        }
        $this->currentSeason = Season::getCurrent();
        $this->currentMatchday = Matchday::getCurrent();
        $this->currentLeague = $leagues[$_SESSION['league_view']];
        //$this->currentLeague = $leagues[1];
        $endDate = Matchday::getTargetCountdown();
        foreach ($this->templates as $savant) {
            $savant->assign('endDate', date_parse($endDate->format("Y-m-d H:i:s")));
            $savant->assign('timestamp', $endDate->getTimestamp());
            $savant->assign('currentMatchday',$this->currentMatchday->getId());
            $savant->assign('isSeasonEnded',$this->currentMatchday->isSeasonEnded());
            $savant->assign('leagues', $leagues);
            $savant->assign('route',$this->route);
            $savant->assign('router', $this->router);
            $savant->assign('request',$this->request);
        }
        $this->quickLinks = new QuickLinks($this->request,$this->router,$this->route);
        $this->templates['navbar']->assign('entries',$this->pages);
        $this->initializeNotification();
        $this->templates['navbar']->assign('notification',$this->notification);
    }

    private function initializeNotification() {
         if(!$this->currentMatchday->isSeasonEnded()) {
            $lineup = Lineup::getFormazioneBySquadraAndGiornata($_SESSION['user_id'],$this->currentMatchday->getId());
            if(empty($lineup)) {
                $this->notification[] = new Notify(Notify::LEVEL_MEDIUM,'Non hai ancora impostato la formazione per questa giornata',$this->router->generate('lineup'));
            }
        }
        if($_SESSION['logged']) {
            $inactivePlayers = Member::getInactiveByTeam(User::getById($_SESSION['user_id'])->getTeam());
            if(!empty($inactivePlayers) && count(Transfert::getTrasferimentiByIdSquadra($_SESSION['user_id'])) < $_SESSION['league_data']->number_transfert) {
                $this->notification[] = new Notify(Notify::LEVEL_HIGH,'Un tuo giocatore non è più nella lista!',$this->router->generate('transfert_index'));
            }
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

 