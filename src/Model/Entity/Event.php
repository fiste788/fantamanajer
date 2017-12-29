<?php
namespace App\Model\Entity;

use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Event Entity.
 *
 * @property int $id
 * @property Time $created_at
 * @property int $type
 * @property int $external
 * @property int $team_id
 * @property Team $team
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

    public function __construct(array $properties = [], array $options = [])
    {
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

    protected function processEvent()
    {
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

    protected function processNewArticle()
    {
        $article = TableRegistry::get('Articles')->get($this->external);
        $this->title = $this->team->name . ' ha rilasciato una conferenza stampa intitolata ' . $article->title;
        $this->body = $article->body;
        $this->icon = 'message';
        $this->link = [
            'controller' => 'Articles',
            'action' => 'view'
        ];
    }

    protected function processNewPlayerSelection()
    {
        $this->title = $this->team->name . ' ha selezionato un giocatore per l\'acquisto';
        $this->body = '';
        $this->link = '';
        $this->icon = 'gavel';
    }

    protected function processNewLineup()
    {
        $lineup = TableRegistry::get('Lineups')->get($this->external, ['contain' => ['Teams', 'Matchdays', 'Dispositions.Members.Players']]);
        $this->title = $this->team->name . ' ha impostato la formazione per la giornata ' . $lineup->matchday->number;
        $regular = array_splice($lineup->dispositions, 0, 11);
        $this->body = 'Formazione: ';
        foreach ($regular as $disposition) {
            $this->body .= $disposition->member->player->surname . ', ';
        }
        $this->body = substr($this->body, 0, -2);
        $this->icon = 'star';
        //$this->link = Router::generate('lineup', array('matchday_id' => $lineup->matchday_id, 'team_id' => $lineup->team_id));
    }

    protected function processNewTransfert()
    {
        $transfert = TableRegistry::get('Transferts')->get($this->external, ['contain' => ['OldMembers.Players', 'NewMembers.Players']]);
        $this->title = $this->team->name . ' ha effettuato un trasferimento';
        $this->body = $this->team->name . ' ha ceduto il giocatore ' . $transfert->old_member->player->full_name . ' e ha acquistato ' . $transfert->new_member->player->full_name;
        //$this->link = Router::generate('transfert', array('id' => $transfert->getTeamId()));
        $this->link = '';
        $this->icon = 'transform';
    }

    protected function processNewPlayer()
    {
        $member = TableRegistry::get('Members')->get($this->external, ['contain' => ['Players', 'Clubs', 'Roles']]);
        $player = $member->player;
        if (!is_null($member)) {
            $this->title = $player->name . ' ' . $player->surname . ' (' . $member->club->name . ') inserito nella lista giocatori';
            $this->body = ucwords($member->role->determinant) . ' ' . strtolower($member->role->singolar) . ' ' . $player . ' ora fa parte della rosa ' . $member->club->partitive . ' ' . $member->club->name;
            $this->link = Router::generate('players_show', ['edit' => 'view', 'id' => $player->id]);
        }
    }

    protected function processDeletePlayer()
    {
        $member = TableRegistry::get('Members')->get($this->external, ['contain' => ['Players', 'Clubs', 'Roles']]);
        $player = $member->player;
        $this->title = $player . ' (ex ' . $member->club->name . ') non fa più parte della lista giocatori';
        $this->body = ucwords($member->role->determinant) . ' ' . strtolower($member->role->singolar) . ' ' . $player . ' non è più un giocatore ' . $member->club->partitive . ' ' . $member->club->name;
        $this->link = Router::generate('player_show', ['edit' => 'view', 'id' => $member->id]);
    }

    protected function processEditClub()
    {
        $member = TableRegistry::get('Members')->get($this->external, ['contain' => ['Players', 'Clubs', 'Roles']]);
        $player = $member->player;
        $this->title = ucwords($member->club->determinant) . ' ' . $member->club->name . ' ha ingaggiato ' . $player;
        $this->body = '';
        $this->link = Router::generate('player_show', ['edit' => 'view', 'id' => $member->id]);
    }
}
