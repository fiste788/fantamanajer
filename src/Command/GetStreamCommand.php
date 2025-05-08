<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\Championship;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use GetStream\Stream\Client;
use GetStream\Stream\FeedInterface;
use Override;

class GetStreamCommand extends Command
{
    /**
     * @var \GetStream\Stream\Client
     */
    private Client $client;

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();

        /** @var array<string> $config */
        $config = Configure::read('GetStream.default');
        $this->client = new Client($config['appKey'], $config['appSecret']);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Test');

        return $parser;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    #[Override]
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $timelineFeed = $this->client->feed('timeline', 'general');

        $championshipsTable = $this->fetchTable('Championships');
        /** @var array<\App\Model\Entity\Championship> $championsips */
        $championsips = $championshipsTable->find()->contain(['Teams'])->all();
        foreach ($championsips as $championsip) {
            $this->processChampionship($championsip, $timelineFeed);
        }

        return CommandInterface::CODE_SUCCESS;

        /*
        $io->out(print_r($activities,1));
        $enrich = new Enrich();
        $enrich->setEnrichingFields(['actor']);
        $richedActivities = $enrich->enrichActivities($activities['results']);
        $io->out(print_r($richedActivities,1));*/
    }

    /**
     * @param \App\Model\Entity\Championship $championship Championship
     * @param \GetStream\Stream\FeedInterface $timelineFeed Feed
     * @return void
     * @throws \GetStream\Stream\StreamFeedException
     */
    private function processChampionship(Championship $championship, FeedInterface $timelineFeed): void
    {
        $championshipFeed = $this->client->feed('championship', (string)$championship->id);
        $championshipFeed->follow($timelineFeed->getSlug(), $timelineFeed->getUserId());
        foreach ($championship->teams as $team) {
            $teamFeed = $this->client->feed('team', (string)$team->id);
            $userFeed = $this->client->feed('user', (string)$team->user_id);
            $userFeed->follow($teamFeed->getSlug(), $teamFeed->getUserId());
            $championshipFeed->follow($teamFeed->getSlug(), $teamFeed->getUserId());
        }
    }
}
