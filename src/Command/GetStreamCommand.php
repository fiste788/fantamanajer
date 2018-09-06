<?php

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use GetStream\Stream\Client;
use StreamCake\Enrich;

class GetStreamCommand extends Command
{

    /**
     *
     * @var Client
     */
    private $client;

    public function initialize()
    {
        parent::initialize();
        $config = Configure::read('GetStream.default');
        $this->client = new Client($config['appKey'], $config['appSecret']);
    }

    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser->setDescription('Test');

        return $parser;
    }

    /**
     * @var \App\Model\Entity\Team[] $teams
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $timelineFeed = $this->client->feed('timeline', 'general');
        $championsipTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Championships');
        $championsips = $championsipTable->find()->contain(['Teams'])->all();
        foreach ($championsips as $championsip) {
            $this->processChampionship($championsip, $timelineFeed);
        }

        /*
        $io->out(print_r($activities,1));
        $enrich = new Enrich();
        $enrich->setEnrichingFields(['actor']);
        $richedActivities = $enrich->enrichActivities($activities['results']);
        $io->out(print_r($richedActivities,1));*/
    }

    /**
     *
     * @param \App\Model\Entity\Championship $championship
     * @param \GetStream\Stream\Feed $timelineFeed
     */
    private function processChampionship($championship, $timelineFeed)
    {
        $championshipFeed = $this->client->feed('championship', $championship->id);
        $championshipFeed->follow($timelineFeed->getSlug(), $timelineFeed->getUserId());
        foreach ($championship->teams as $team) {
            $teamFeed = $this->client->feed('team', $team->id);
            $userFeed = $this->client->feed('user', $team->user_id);
            $userFeed->follow($teamFeed->getSlug(), $teamFeed->getUserId());
            $championshipFeed->follow($teamFeed->getSlug(), $teamFeed->getUserId());
        }
    }
}
