<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Event Entity.
 *
 * @property int $id
 * @property \Cake\I18n\Time $created_at
 * @property int $type
 * @property int $external
 * @property int $team_id
 * @property \App\Model\Entity\Team $team
 */
class Event extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
    
    public function __construct(array $properties = array(), array $options = array()) {
        parent::__construct($properties, $options);
        $this->processEvent();
    }
    
    const NEW_ARTICLE = 1;
    const NEW_PLAYER_SELECTION = 2;
    const NEW_LINEUP = 3;
    const NEW_TRANSFERT = 4;
    const NEW_PLAYER = 5;
    const DELETE_PLAYER = 6;
    const EDIT_CLUB = 7;
    
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
        $article = \Cake\ORM\TableRegistry::get('Articles')->get($this->external);
        $this->title = $this->team->name . ' ha rilasciato una conferenza stampa intitolata ' . $article->title;
        $this->body = '';
        if (!empty($article->subtitle)) {
            $this->body = '<em>' . $article->subtitle . '</em><br />';
        }
        $this->body .= $article->body;
        $this->link = [
            'controller' => 'Articles',
            'action' => 'view'
        ];
    }

    protected function processNewPlayerSelection() {
        $this->title = $this->team->name . ' ha selezionato un giocatore per l\'acquisto';
        $this->body = ' ';
        $this->link = '';
    }

    protected function processNewLineup() {
        $lineup = \Cake\ORM\TableRegistry::get('Lineups')->get($this->external);
        $this->title = $this->team->name . ' ha impostato la formazione per la giornata ' . $lineup->getMatchdayId();
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
}
