<?php

namespace App\Event;

use App\Model\Entity\Article;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use GetStream\Stream\Client;

class GetStreamEventListener implements EventListenerInterface
{
    /**
     *
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $config = Configure::read('GetStream.default');
        $this->client = new Client($config['appKey'], $config['appSecret']);
    }

    public function implementedEvents()
    {
        return [
            'Fantamanajer.newArticle' => 'addNewArticleActivity',
            'Fantamanajer.newLineup' => 'addNewLineupActivity',
            'Fantamanajer.newMemberSelection' => 'addNewMemberSelectionActivity',
            'Fantamanajer.newMemberTransfert' => 'addNewMemberTransfertActivity',
            'Fantamanajer.changeMember' => 'changeMember',
            'Fantamanajer.memberTransferts' => 'memberTransferts'
        ];
    }

    /**
     *
     * @param Event $event
     * @param Article $article
     */
    public function addNewArticleActivity($event, $article)
    {
        $feed = $this->client->feed('team', $article->team_id);
        $feed->setGuzzleDefaultOption('verify', false);
        $feed->addActivity([
            'actor' => 'Team:' . $article->team_id,
            'verb' => 'post',
            'object' => 'Article:' . $article->id
        ]);
    }

    /**
     *
     * @param Event $event
     * @param \App\Model\Entity\Lineup $lineup
     */
    public function addNewLineupActivity($event, $lineup)
    {
        $feed = $this->client->feed('team', $lineup->team_id);
        $feed->setGuzzleDefaultOption('verify', false);
        $feed->addActivity([
            'actor' => 'Team:' . $lineup->team_id,
            'verb' => 'lineup',
            'object' => 'Lineup:' . $lineup->id
        ]);
    }

    /**
     *
     * @param Event $event
     * @param \App\Model\Entity\Selection $selection
     */
    public function addNewMemberSelectionActivity($event, $selection)
    {
        $feed = $this->client->feed('team', $selection->team_id);
        $feed->setGuzzleDefaultOption('verify', false);
        $feed->addActivity([
            'actor' => 'Team:' . $selection->team_id,
            'verb' => 'selection',
            'object' => 'Selection:' . $selection->id
        ]);
    }

    /**
     *
     * @param Event $event
     * @param \App\Model\Entity\Transfert $transfert
     */
    public function addNewMemberTransfertActivity($event, $transfert)
    {
        $feed = $this->client->feed('team', $transfert->team_id);
        $feed->setGuzzleDefaultOption('verify', false);
        $feed->addActivity([
            'actor' => 'Team:' . $transfert->team_id,
            'verb' => 'transfert',
            'object' => 'Transfert:' . $transfert->id
        ]);
    }

    /**
     *
     * @param Event $event
     * @param \App\Model\Entity\Member $member
     */
    public function changeMember($event, $member)
    {
        if ($member->isNew() || $member->isDirty('club_id') || ($member->isDirty('active') && $member->active)) {
            $feed = $this->client->feed('club', $member->club_id);
            $feed->setGuzzleDefaultOption('verify', false);
            $feed->addActivity([
                'actor' => 'Club:' . $member->club_id,
                'verb' => 'engage',
                'object' => 'Member:' . $member->id,
                'to' => 'timeline:general'
            ]);
            if ($member->isDirty('club_id') && !$member->isNew() && $member->active) {
                $feed = $this->client->feed('club', $member->getOriginal('club_id'));
                $feed->setGuzzleDefaultOption('verify', false);
                $feed->addActivity([
                    'actor' => 'Club:' . $member->getOriginal('club_id'),
                    'verb' => 'sell',
                    'object' => 'Member:' . $member->id,
                    'to' => 'timeline:general'
                ]);
            }
        } elseif ($member->isDirty('active') && !$member->active) {
            $feed = $this->client->feed('club', $member->getOriginal('club_id'));
            $feed->setGuzzleDefaultOption('verify', false);
            $feed->addActivity([
                'actor' => 'Club:' . $member->getOriginal('club_id'),
                'verb' => 'sell',
                'object' => 'Member:' . $member->id,
                'to' => 'timeline:general'
            ]);
        }
    }

    /**
     *
     * @param Event $event
     * @param \App\Model\Entity\Member[] $buys
     * @param \App\Model\Entity\Member[] $sells
     */
    public function memberTransferts($event, $buys, $sells)
    {
        foreach ($buys as $club => $members) {
            foreach ($members as $member) {
                $activities = [
                    'actor' => 'Club:' . $club,
                    'verb' => 'engage',
                    'object' => 'Member:' . $member->id,
                    'to' => 'timeline:general'
                ];
            }
            $feed = $this->client->feed('club', $club);
            $feed->setGuzzleDefaultOption('verify', false);
            $feed->addActivities($activities);
        }
        foreach ($sells as $club => $member) {
            foreach ($members as $member) {
                $activities = [
                    'actor' => 'Club:' . $club,
                    'verb' => 'sell',
                    'object' => 'Member:' . $member->id,
                    'to' => 'timeline:general'
                ];
            }
            $feed = $this->client->feed('club', $club);
            $feed->setGuzzleDefaultOption('verify', false);
            $feed->addActivities($activities);
        }
    }
}
