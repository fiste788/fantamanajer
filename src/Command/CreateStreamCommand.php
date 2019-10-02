<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use GetStream\Stream\Client;

class CreateStreamCommand extends Command
{
    /**
     *
     * @var \GetStream\Stream\Client
     */
    private $client;

    /**
     * Undocumented function
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->client = new Client('sgyn7qrwwa6g', 'hbgt5gxen8dzs2mx8fypqmszykb7x62d6pxpn38m62e5jrreaz5jh289qsmra23h');
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Console\ConsoleOptionParser $parser ConsoleOptionParser
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Test');

        return $parser;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io ConsoleIO
     * @return int|null
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $championshipFeed = $this->client->feed('championship', 12);
        $teamFeed = $this->client->feed('team', '49');
        $teamFeed->follow('championship', 12);

        return 1;
        //$io->out($championshipFeed->getToken());
    }
}
