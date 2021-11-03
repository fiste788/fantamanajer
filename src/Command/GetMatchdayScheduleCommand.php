<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use App\Traits\CurrentMatchdayTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Http\Client;
use Cake\I18n\FrozenTime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\SeasonsTable $Seasons
 */
class GetMatchdayScheduleCommand extends Command
{
    use CurrentMatchdayTrait;

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->fetchTable('Matchdays');
        $this->fetchTable('Seasons');
        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        parent::buildOptionParser($parser);
        $parser->addArgument('matchday');
        $parser->addArgument('season');

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $season = $args->getArgument('season') ?
            $this->Seasons->get($args->getArgument('season')) : $this->currentSeason;
        if (!$args->hasArgument('matchday')) {
            $matchday = $this->currentMatchday;
        } else {
            /** @var \App\Model\Entity\Matchday|null $matchday */
            $matchday = $this->Matchdays->find()->where([
                'number' => $args->getArgument('matchday'),
                'season_id' => $season->id,
            ])->first();
        }

        return $matchday && $this->exec($season, $matchday, $io) ?
            CommandInterface::CODE_SUCCESS : CommandInterface::CODE_ERROR;
    }

    /**
     * Exec
     *
     * @param \App\Model\Entity\Season $season Season
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param \Cake\Console\ConsoleIo $io Io
     * @return \Cake\I18n\FrozenTime|false|null
     * @throws \Cake\Console\Exception\StopException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function exec(Season $season, Matchday $matchday, ConsoleIo $io)
    {
        $year = ((string)$season->year) . '-' . substr((string)($season->year + 1), 2, 2);
        $url = "/it/serie-a/calendario-e-risultati/$year/UNICO/UNI/$matchday->number";
        $io->verbose('Downloading page ' . $url);
        $client = new Client(
            [
                'host' => 'www.legaseriea.it',
                'redirect' => 5,
                'timeout' => 60,
            ]
        );

        $response = $client->get($url);
        if ($response->isRedirect()) {
            $response = $client->get($response->getHeaderLine('Location'));
        }
        if ($response->isOk()) {
            $io->verbose('Response OK');
            $crawler = new Crawler();
            $crawler->addContent($response->getStringBody());
            $datiPartita = $crawler->filter('.datipartita')->first();
            if ($datiPartita->count()) {
                $box = $datiPartita->filter('p')->first()->filter('span');
                $date = trim($box->text());
                if ($date != '') {
                    $io->success($date);
                    if (!strpos($date, ' ')) {
                        $out = FrozenTime::createFromFormat('!d/m/Y', $date);
                        $out = $out->setTime(18, 0, 0, 0);
                    } else {
                        $out = FrozenTime::createFromFormat('!d/m/Y H:i', $date);
                    }

                    return $out;
                } else {
                    $io->error('Cannot find date');
                    $this->abort();
                }
            } else {
                $io->error('Cannot find .datipartita');
                $this->abort();
            }
        } else {
            $io->error((string)$response->getStatusCode(), 1);
            $io->error('Cannot connect to ' . $url);
            $this->abort();
        }

        return null;
    }
}
