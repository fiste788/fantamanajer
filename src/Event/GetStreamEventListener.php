<?php
declare(strict_types=1);

namespace App\Event;

use Cake\Core\Configure;
use Cake\Event\EventListenerInterface;
use GetStream\Stream\Client;

class GetStreamEventListener implements EventListenerInterface
{
    /**
     *
     * @var \GetStream\Stream\Client
     */
    private $client;

    public function __construct()
    {
        $config = Configure::read('GetStream.default');
        $this->client = new Client($config['appKey'], $config['appSecret']);
    }

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
     * @param \Cake\Event\Event $event
     * @param \App\Model\Entity\Article $article
     */
    public function addNewArticleActivity($event, $article)
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
     * @param \Cake\Event\Event $event
     * @param \App\Model\Entity\Lineup $lineup
     */
    public function addNewLineupActivity($event, $lineup)
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
     * @param \Cake\Event\Event $event
     * @param \App\Model\Entity\Selection $selection
     */
    public function addNewMemberSelectionActivity($event, $selection)
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
     * @param \Cake\Event\Event $event
     * @param \App\Model\Entity\Transfert $transfert
     */
    public function addNewMemberTransfertActivity($event, $transfert)
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
     * @param \Cake\Event\Event $event
     * @param \App\Model\Entity\Member $member
     */
    public function changeMember($event, $member)
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
     * @param \Cake\Event\Event $event
     * @param \App\Model\Entity\Member[] $buys
     * @param \App\Model\Entity\Member[] $sells
     */
    public function memberTransferts($event, $buys, $sells)
    {
        $activities = [];
        foreach ($buys as $club => $members) {
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
