<?php

namespace App\Command;

use App\Model\Entity\Championship;
use App\Model\Entity\Team;
use App\Model\Table\ChampionshipsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use GetStream\Stream\Client;
use GetStream\Stream\Feed;

/**
 * @property ChampionshipsTable $Championships
 */
class GetStreamCommand extends Command
{

    /**
     *
     * @var Client
     */
    private $client;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Championships');
        $config = Configure::read('GetStream.default');
        $this->client = new Client($config['appKey'], $config['appSecret']);
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Test');

        return $parser;
    }

    /**
     * @var Team[] $teams
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $timelineFeed = $this->client->feed('timeline', 'general');
        $championsips = $this->Championships->find()->contain(['Teams'])->all();
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
     * @param Championship $championship
     * @param Feed $timelineFeed
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
