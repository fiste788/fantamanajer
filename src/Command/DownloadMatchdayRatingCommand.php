<?php

namespace App\Command;

use App\Model\Entity\Season;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Http\Client;
use Symfony\Component\DomCrawler\Crawler;

class DownloadMatchdayRatingCommand extends Command
{

    /**
     *
     * @var Client
     */
    private $client;

    public function initialize()
    {
        parent::initialize();
        $this->client = new Client();
        $this->client->setConfig('ssl_verify_peer', false);
    }

    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser->setDescription('Download ratings from maxigames');
        $parser->addArgument('matchday', [
            'help' => 'The number of matchday of current season',
            'required' => true
        ]);

        return $parser;
    }

    /**
     *
     * @return Season
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->exec($args->getArgument('matchday'), $io);
    }

    public function exec($matchday, ConsoleIo $io)
    {
        $url = $this->getDropboxUrl($matchday, $io);
        if ($url) {
            return $this->downloadDropboxFile($url, $matchday, $io);
        }
    }

    private function getDropboxUrl($matchday, ConsoleIo $io)
    {
        $io->out("Search ratings on maxigames");
        $url = "https://maxigames.maxisoft.it/downloads.php";
        $io->verbose("Downloading " . $url);
        $response = $this->client->get($url);
        if ($response->isOk()) {
            $this->out("Maxigames found");
            $crawler = new Crawler();
            $crawler->addContent($response->body());
            $td = $crawler->filter("#content td:contains('Giornata $matchday')");
            if ($td->count() > 0) {
                return $td->nextAll()->filter("a")->attr("href");
            }
        } else {
            $io->err("Could not connect to Maxigames");
            $this->abort();
        }
    }

    private function downloadDropboxFile($url, $matchday, ConsoleIo $io)
    {
        $this->verbose("Downloading " . $url);
        $response = $this->client->get($url);
        if ($response->isOk()) {
            $crawler = new Crawler();
            $crawler->addContent($response->body());
            $button = $crawler->filter("#default_content_download_button");
            if ($button->count()) {
                $url = $button->attr("href");
            } else {
                $url = str_replace("www", "dl", $url);
            }
            $io->out("Downloading $url in tmp dir");
            $file = TMP . $matchday . '.mxm';
            file_put_contents($file, file_get_contents($url));

            return $file;
        }
    }
}
