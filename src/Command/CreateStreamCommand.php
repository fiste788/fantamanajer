<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use GetStream\Stream\Client;

class CreateStreamCommand extends Command
{
    /**
     * @var \GetStream\Stream\Client
     */
    private $client;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->client = new Client('sgyn7qrwwa6g', 'hbgt5gxen8dzs2mx8fypqmszykb7x62d6pxpn38m62e5jrreaz5jh289qsmra23h');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Test');

        return $parser;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \GetStream\Stream\StreamFeedException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $championshipFeed = $this->client->feed('championship', (string)12);
        $teamFeed = $this->client->feed('team', '49');
        $teamFeed->follow('championship', $championshipFeed->getSlug());

        return CommandInterface::CODE_SUCCESS;
        //$io->out($championshipFeed->getToken());
    }
}
