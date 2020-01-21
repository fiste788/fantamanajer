<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Http\Client;
use Symfony\Component\DomCrawler\Crawler;
use const TMP;

class DownloadMatchdayRatingCommand extends Command
{
    /**
     *
     * @var \Cake\Http\Client
     */
    private $client;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->client = new Client();
        $this->client->setConfig('ssl_verify_peer', false);
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Download ratings from maxigames');
        $parser->addArgument('matchday', [
            'help' => 'The number of matchday of current season',
            'required' => true,
        ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $matchday = (int)$args->getArgument('matchday');

        return $this->exec($matchday, $io) ? CommandInterface::CODE_SUCCESS : CommandInterface::CODE_ERROR;
    }

    /**
     * Exec
     *
     * @param int $matchday Matchday
     * @param \Cake\Console\ConsoleIo $io Io
     * @return string|null
     */
    public function exec(int $matchday, ConsoleIo $io): ?string
    {
        $url = $this->getDropboxUrl($matchday, $io);
        if ($url) {
            return $this->downloadDropboxFile($url, $matchday, $io);
        }

        return null;
    }

    /**
     * Get dropbox url
     *
     * @param int $matchday Matchday
     * @param \Cake\Console\ConsoleIo $io Io
     * @return string|null
     */
    private function getDropboxUrl(int $matchday, ConsoleIo $io): ?string
    {
        $io->out("Search ratings on maxigames");
        $url = "https://maxigames.maxisoft.it/downloads.php";
        $io->verbose("Downloading " . $url);
        $response = $this->client->get($url);
        if ($response->isOk()) {
            $io->out("Maxigames found");
            $crawler = new Crawler();
            $crawler->addContent($response->getStringBody());
            $td = $crawler->filter("#content td:contains('Giornata $matchday')");
            if ($td->count() > 0) {
                return $td->nextAll()->filter("a")->attr("href");
            }
        } else {
            $io->err("Could not connect to Maxigames");
            $this->abort();
        }

        return null;
    }

    /**
     * Download dropbox file
     *
     * @param string $url Url
     * @param int $matchday Matchday
     * @param \Cake\Console\ConsoleIo $io IO
     *
     * @return null|string
     */
    private function downloadDropboxFile(string $url, int $matchday, ConsoleIo $io): ?string
    {
        $io->verbose("Downloading " . $url);
        $response = $this->client->get($url);
        if ($response->isOk()) {
            $crawler = new Crawler();
            $crawler->addContent($response->getStringBody());
            $button = $crawler->filter("#default_content_download_button");
            if ($button->count()) {
                $dropboxUrl = $button->attr("href");
            } else {
                $dropboxUrl = str_replace("www", "dl", $url);
            }
            if ($dropboxUrl != null) {
                $io->out("Downloading $dropboxUrl in tmp dir");
                $file = TMP . (string)$matchday . '.mxm';
                file_put_contents($file, file_get_contents($dropboxUrl));

                return $file;
            }
        }

        return null;
    }
}
