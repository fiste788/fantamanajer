<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\EventsTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use Lib\Router;
use PDO;

class Event extends EventsTable {

    const NEW_ARTICLE = 1;
    const NEW_PLAYER_SELECTION = 2;
    const NEW_LINEUP = 3;
    const NEW_TRANSFERT = 4;
    const NEW_PLAYER = 5;
    const DELETE_PLAYER = 6;
    const EDIT_CLUB = 7;
    
    /**
     *
     * @var string
     */
    public $title;
    
    /**
     *
     * @var string
     */
    public $content;
    
    /**
     *
     * @var string
     */
    public $link;
    
    public function __construct() {
        parent::__construct();
        $this->processEvent();
    }

    public static function deleteByIdExternalAndType($external, $type) {
        $q = "DELETE
		FROM events WHERE external = :external AND type = :type";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':external', $external, PDO::PARAM_INT);
        $exe->bindValue(':type', $type, PDO::PARAM_INT);
        return $exe->execute();
        //return ConnectionFactory::getFactory()->getConnection()->exec($q);
    }

    /**
     *
     * @param int $leagueId
     * @param int|null $type
     * @param int $min
     * @param int $max
     * @return Event[]
     */
    public static function getEvents($leagueId, $type = NULL, $min = 0, $max = 10) {
        $q = "SELECT events.*,teams.name
		FROM events LEFT JOIN teams ON events.team_id = teams.id";
        if ($leagueId != NULL) {
            $q .= " WHERE (events.league_id = '" . $leagueId . "' OR events.league_id IS NULL)";
        }
        if ($type != NULL && $type != 0) {
            $q .= " AND type = '" . $type . "'";
        }
        $q .= " ORDER BY created_at DESC
		LIMIT " . $min . "," . $max . ";";
        FirePHP::getInstance()->log($q);
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        $values = $exe->fetchAll(PDO::FETCH_CLASS, __CLASS__);
        return $values;
    }

    protected function processEvent() {
        //$event = $event || $this;
        switch ($this->type) {
            case self::NEW_ARTICLE:
                $this->processNewArticle();
                break;
            case self::NEW_PLAYER_SELECTION:
                $this->processNewPlayerSelection();
                break;
            case self::NEW_LINEUP:
                $this->processNewLineup();
                break;
            case self::NEW_TRANSFERT:
                $this->processNewTransfert();
                break;
            case self::NEW_PLAYER:
                $this->processNewPlayer();
                break;
            case self::DELETE_PLAYER:
                $this->processDeletePlayer();
                break;
            case self::EDIT_CLUB:
                $this->processEditClub();
                break;
        }
    }

    protected function processNewArticle() {
        $article = Article::getById($this->external);
        $this->title = $this->name . ' ha rilasciato una conferenza stampa intitolata ' . $article->title;
        $this->content = '';
        if (!empty($article->subtitle)) {
            $this->content = '<em>' . $article->subtitle . '</em><br />';
        }
        $this->content .= $article->text;
        $this->link = Router::generate('articles', array('matchday_id' => $article->getMatchdayId()));
    }

    protected function processNewPlayerSelection() {
        $this->title = $this->name . ' ha selezionato un giocatore per l\'acquisto';
        $this->content = ' ';
        $this->link = '';
    }

    protected function processNewLineup() {
        $lineup = Lineup::getById($this->external);
        $this->title = $this->name . ' ha impostato la formazione per la giornata ' . $lineup->getMatchdayId();
        $regular = $this->players;
        $regular = array_splice($regular, 0, 11);
        $this->content = 'Formazione: ';
        foreach ($regular as $member) {
            $this->content .= $member->player->surname . ', ';
        }
        $this->content = substr($this->content, 0, -2);
        $this->link = Router::generate('lineup', array('matchday_id' => $lineup->getMatchdayId(), 'team_id' => $lineup->getTeamId()));
    }

    protected function processNewTransfert() {
        $transfert = Transfert::getById($this->external);
        $this->title = $this->name . ' ha effettuato un trasferimento';
        if (!is_null($this->external)) {
            Member::getById($transfert->getNewMember());
            $this->content = $this->name . ' ha ceduto il giocatore ' . $transfert->getOldMember()->getPlayer()->getSurname() . ' e ha acquistato ' . $transfert->getNewMember()->getPlayer()->getSurname();
            $this->link = Router::generate('transfert', array('id' => $transfert->getTeamId()));
        }
    }

    protected function processNewPlayer() {
        $member = Member::getById($this->external);
        $player = $member->getPlayer();
        if (!is_null($member)) {
            $this->title = $player->name . ' ' . $player->surname . ' (' . $player->getClub()->getName() . ') inserito nella lista giocatori';
            $this->content = ucwords($this->roles[$member->role_id])->getDeterminant() . ' ' . strtolower($this->roles[$member->role_id]->getSingolar()) . ' ' . $player . ' ora fa parte della rosa ' . $member->getClub()->partitive . ' ' . $member->getClub()->name . ', pertanto è stato inserito nella lista giocatori';
            $this->link = Router::generate('players_show', array('edit' => 'view', 'id' => $player->id));
        }
    }

    protected function processDeletePlayer() {
        $member = Member::getById($this->external);
        $player = $member->getPlayer();
        $this->title = $player . ' (ex ' . $member->getClub()->nome . ') non fa più parte della lista giocatori';
        $this->content = ucwords($this->roles[$member->role_id])->getDeterminant() . ' ' . strtolower($this->roles[$member->role_id]->getSingolar()) . ' ' . $player . ' non è più un giocatore ' . $member->getClub()->partitive . ' ' . $member->getClub()->name;
        $this->link = Router::generate('player_show', array('edit' => 'view', 'id' => $member->id));
    }

    protected function processEditClub() {
        $member = Member::getById($this->external);
        $player = $member->getPlayer();
        $this->title = ucwords($member->getClub()->determinant) . ' ' . $member->getclub()->name . ' ha ingaggiato ' . $player;
        $this->content = '';
        $this->link = Router::generate('player_show', array('edit' => 'view', 'id' => $member->id));
    }

    public function check(array $array) {
        return TRUE;
    }

}
