<?php
declare(strict_types=1);

namespace App\Event;

use App\Model\Entity\Article;
use App\Model\Entity\Lineup;
use App\Model\Entity\Member;
use App\Model\Entity\Selection;
use App\Model\Entity\Transfert;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use GetStream\Stream\Client;

class GetStreamEventListener implements EventListenerInterface
{
    /**
     *
     * @var \GetStream\Stream\Client
     */
    private $client;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $config = Configure::read('GetStream.default');
        $this->client = new Client($config['appKey'], $config['appSecret']);
    }

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Fantamanajer.newArticle' => 'addNewArticleActivity',
            'Fantamanajer.newLineup' => 'addNewLineupActivity',
            'Fantamanajer.newMemberSelection' => 'addNewMemberSelectionActivity',
            'Fantamanajer.newMemberTransfert' => 'addNewMemberTransfertActivity',
            'Fantamanajer.changeMember' => 'changeMember',
            'Fantamanajer.memberTransferts' => 'memberTransferts',
        ];
    }

    /**
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\Article $article Article
     * @return void
     */
    public function addNewArticleActivity(Event $event, Article $article): void
    {
        $feed = $this->client->feed('team', (string)$article->team_id);
        $feed->setGuzzleDefaultOption('verify', false);
        $feed->addActivity([
            'actor' => 'Team:' . $article->team_id,
            'verb' => 'post',
            'object' => 'Article:' . $article->id,
        ]);
    }

    /**
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @return void
     */
    public function addNewLineupActivity(Event $event, Lineup $lineup): void
    {
        $feed = $this->client->feed('team', (string)$lineup->team_id);
        $feed->setGuzzleDefaultOption('verify', false);
        $feed->addActivity([
            'actor' => 'Team:' . $lineup->team_id,
            'verb' => 'lineup',
            'object' => 'Lineup:' . $lineup->id,
        ]);
    }

    /**
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\Selection $selection Selection
     * @return void
     */
    public function addNewMemberSelectionActivity(Event $event, Selection $selection): void
    {
        $feed = $this->client->feed('team', (string)$selection->team_id);
        $feed->setGuzzleDefaultOption('verify', false);
        $feed->addActivity([
            'actor' => 'Team:' . $selection->team_id,
            'verb' => 'selection',
            'object' => 'Selection:' . $selection->id,
        ]);
    }

    /**
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\Transfert $transfert Transfert
     * @return void
     */
    public function addNewMemberTransfertActivity(Event $event, Transfert $transfert): void
    {
        $feed = $this->client->feed('team', (string)$transfert->team_id);
        $feed->setGuzzleDefaultOption('verify', false);
        $feed->addActivity([
            'actor' => 'Team:' . $transfert->team_id,
            'verb' => 'transfert',
            'object' => 'Transfert:' . $transfert->id,
        ]);
    }

    /**
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\Member $member Member
     * @return void
     */
    public function changeMember(Event $event, Member $member): void
    {
        if ($member->isNew() || $member->isDirty('club_id') || ($member->isDirty('active') && $member->active)) {
            $feed = $this->client->feed('club', (string)$member->club_id);
            $feed->setGuzzleDefaultOption('verify', false);
            $feed->addActivity([
                'actor' => 'Club:' . $member->club_id,
                'verb' => 'engage',
                'object' => 'Member:' . $member->id,
                'to' => 'timeline:general',
            ]);
            if ($member->isDirty('club_id') && !$member->isNew() && $member->active) {
                $feed = $this->client->feed('club', (string)$member->getOriginal('club_id'));
                $feed->setGuzzleDefaultOption('verify', false);
                $feed->addActivity([
                    'actor' => 'Club:' . $member->getOriginal('club_id'),
                    'verb' => 'sell',
                    'object' => 'Member:' . $member->id,
                    'to' => 'timeline:general',
                ]);
            }
        } elseif ($member->isDirty('active') && !$member->active) {
            $feed = $this->client->feed('club', (string)$member->getOriginal('club_id'));
            $feed->setGuzzleDefaultOption('verify', false);
            $feed->addActivity([
                'actor' => 'Club:' . $member->getOriginal('club_id'),
                'verb' => 'sell',
                'object' => 'Member:' . $member->id,
                'to' => 'timeline:general',
            ]);
        }
    }

    /**
     *
     * @param \Cake\Event\Event $event Event
     * @param array $buys Buys
     * @param array $sells Sells
     * @return void
     */
    public function memberTransferts(Event $event, array $buys, array $sells): void
    {
        $activities = [];
        foreach ($buys as $club => $members) {
            /** @var \App\Model\Entity\Member[] $members */
            foreach ($members as $member) {
                $activities = [
                    'actor' => 'Club:' . $club,
                    'verb' => 'engage',
                    'object' => 'Member:' . $member->id,
                    'to' => 'timeline:general',
                ];
            }
            $feed = $this->client->feed('club', $club);
            $feed->setGuzzleDefaultOption('verify', false);
            $feed->addActivities($activities);
        }
        foreach ($sells as $club => $members) {
            /** @var \App\Model\Entity\Member[] $members */
            foreach ($members as $member) {
                $activities = [
                    'actor' => 'Club:' . $club,
                    'verb' => 'sell',
                    'object' => 'Member:' . $member->id,
                    'to' => 'timeline:general',
                ];
            }
            $feed = $this->client->feed('club', $club);
            $feed->setGuzzleDefaultOption('verify', false);
            $feed->addActivities($activities);
        }
    }
}
