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
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\I18n\DateTime;
use DateTimeInterface;
use DateTimeZone;
use Override;
use Symfony\Component\DomCrawler\Crawler;
use function Cake\Core\toString;

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
    #[Override]
    public function initialize(): void
    {
        parent::initialize();
        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        parent::buildOptionParser($parser);
        $parser->addArgument('matchday');
        $parser->addArgument('season');

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null The exit code or null for success
     * @throws \Cake\Core\Exception\CakeException
     */
    #[Override]
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        /** @var \App\Model\Table\SeasonsTable $seasonsTable */
        $seasonsTable = $this->fetchTable('Seasons');
        $season = $args->getArgument('season') != null ?
            $seasonsTable->get($args->getArgument('season')) : $this->currentSeason;
        if (!$args->hasArgument('matchday')) {
            $matchday = $this->currentMatchday;
        } else {
            /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
            $matchdaysTable = $this->fetchTable('Matchdays');
            /** @var \App\Model\Entity\Matchday|null $matchday */
            $matchday = $matchdaysTable->find()->where([
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
     * @return \Cake\I18n\DateTime|false|null
     * @throws \Cake\Console\Exception\StopException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function exec(Season $season, Matchday $matchday, ConsoleIo $io): DateTime|false|null
    {
        $year = ((string)$season->year) . '-' . substr((string)($season->year + 1), 2, 2);
        $url = '/it/serie-a/';
        $io->verbose('Downloading page ' . $url);
        $client = new Client(
            [
                'host' => 'www.legaseriea.it',
                'redirect' => 5,
                'timeout' => 60,
            ],
        );

        $response = $client->get($url);
        if ($response->isOk()) {
            $io->info('Response OK');
            $crawler = new Crawler();
            $crawler->addContent($response->getStringBody());
            $seasonOption = $crawler->filterXPath('//select[@name="season"]/option[text()="' . $year . '"]');
            if ($seasonOption->count()) {
                $seasonId = $seasonOption->first()->attr('value');
                
                if ($seasonId != null) {
                    $matchdayResponse = $client->get('/api/season/' . $seasonId . '/championship/A/matchday?lang=it');
                    
                    /**
                     * @psalm-suppress MixedArrayAccess
                     * @var array<string, mixed> $matchdays
                     */
                    $matchdays = $matchdayResponse->getJson()['data'];
                    
                    /**
                     * @psalm-suppress MixedAssignment
                     */
                    foreach ($matchdays as $matchdayItem) {
                        /**
                         * @psalm-suppress MixedArrayAccess
                         */
                        if ($matchdayItem['description'] == $matchday->number || trim(substr($matchdayItem['title'], -2)) == $matchday->number) {
                            /**
                             * @psalm-suppress MixedOperand
                             */
                            $matchsResponse = $client->get(
                                '/api/match?extra_link&order=oldest&lang=it&season_id=' .
                                    $seasonId .
                                    '&match_day_id=' .
                                    $matchdayItem['id_category'],
                            );
                            /** @var string $date */
                            $date = $matchsResponse->getJson()['data'][0]['date_time'];

                            if ($date != '') {
                                $io->success($date);
                                /**
                                 * @var string $timezone
                                 */
                                $timezone = toString(Configure::read('App.defaultTimezone', 'UTC'));
                                $out = DateTime::createFromFormat(
                                    DateTimeInterface::RFC3339,
                                    $date,
                                    new DateTimeZone('UTC'),
                                );
                                $out = $out->setTimezone($timezone);
                                $io->verbose(print_r($out, true));

                                return $out;
                            } else {
                                $io->error('Cannot find date');
                                $this->abort();
                            }
                        }
                    }

                    $io->error('Cannot find matchday');
                    $this->abort();
                } else {
                    $io->error('Cannot find season id');
                    $this->abort();
                }
            } else {
                $io->error('Cannot find //select[@name="season"]/option[text()="' . $year . '"]');
                $this->abort();
            }
        } else {
            $io->error((string)$response->getStatusCode(), 1);
            $io->error('Cannot connect to ' . $url);
            $this->abort();
        }
    }
}
