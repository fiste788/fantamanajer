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
}
