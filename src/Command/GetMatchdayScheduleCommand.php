<?php

namespace App\Command;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use App\Traits\CurrentMatchdayTrait;
use Cake\Chronos\Chronos;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Http\Client;
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

    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        parent::buildOptionParser($parser);
        $parser->addArgument('matchday');
        $parser->addArgument('season');

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        if (!$args->hasArgument('season')) {
            $season = $this->currentSeason;
        }
        if (!$args->hasArgument('matchday')) {
            $matchday = $this->currentMatchday;
        } else {
            $matchday = $this->Matchdays->find()->where([
                'number' => $args->getArgument('matchday'),
                'season_id' => $season->id
            ])->first();
        }

        $this->exec($season, $matchday, $io);
    }

    public function exec(Season $season, Matchday $matchday, ConsoleIo $io)
    {
        $year = $season->year . "-" . substr($season->year + 1, 2, 2);
        $url = "/it/serie-a/calendario-e-risultati/$year/UNICO/UNI/$matchday->number";
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
            $io->verbose("Response OK");
            $crawler = new Crawler();
            $crawler->addContent($response->body());
            $datiPartita = $crawler->filter(".datipartita")->first();
            if ($datiPartita->count()) {
                $box = $datiPartita->filter("p")->first()->filter("span");
                $date = trim($box->text());
                if ($date != "") {
                    $io->success($date);
                    if (!strpos($date, " ")) {
                        $out = Chronos::createFromFormat("!d/m/Y", $date);
                        $out = $out->setTime(18, 0, 0, 0);
                    } else {
                        $out = Chronos::createFromFormat("!d/m/Y H:i", $date);
                    }

                    return $out;
                } else {
                    $io->error("Cannot find date");
                    $this->abort();
                }
            } else {
                $io->error("Cannot find .datipartita");
                $this->abort();
            }
        } else {
            $io->error($response->getStatusCode(), 1);
            $io->error("Cannot connect to " . $url);
            $this->abort();
        }
    }
}
