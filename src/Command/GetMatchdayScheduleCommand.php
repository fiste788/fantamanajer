<?php

namespace App\Command;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Http\Client;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class GetMatchdayScheduleCommand extends Command
{
    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Matchdays');
        $this->getCurrentMatchday();
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        if (!$args->hasArgument('matchday')) {
            $matchday = $this->currentMatchday;
        }
        if (!$args->hasArgument('season')) {
            $season = $this->currentSeason;
        }
        $this->exec($season, $matchday, $io);
    }

    public function exec(Season $season, Matchday $matchday, ConsoleIo $io)
    {
        $year = $season->year . "-" . substr($season->year + 1, 2, 2);
        $url = "/it/serie-a-tim/calendario-e-risultati/$year/UNICO/UNI/$matchday->number";
        $io->verbose("Downloading page " . $url);
        $client = new Client(
            [
            'host' => 'www.legaseriea.it',
            'redirect' => 5
            ]
        );

        $response = $client->get($url);
        if ($response->isRedirect()) {
            $response = $client->get($response->getHeaderLine('Location'));
        }
        if ($response->isOk()) {
            $crawler = new Crawler();
            $crawler->addContent($response->body());
            $datiPartita = $crawler->filter(".datipartita")->first();
            if ($datiPartita->count()) {
                $box = $datiPartita->filter("p")->first()->filter("span");
                $date = $box->text();
                if ($date != "") {
                    $out = DateTime::createFromFormat("!d/m/Y H:i", $date);
                    $io->info($date);

                    return $out;
                } else {
                    $io->err("Cannot find .datipartita");
                    $this->abort();
                }
            }
        } else {
            $io->err($response->getStatusCode(), 1);
            $io->err("Cannot connect to " . $url);
            $this->abort();
        }
    }
}
