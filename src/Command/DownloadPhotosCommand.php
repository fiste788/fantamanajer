<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Table\MatchdaysTable;
use App\Model\Table\MembersTable;
use App\Model\Table\SeasonsTable;
use App\Traits\CurrentMatchdayTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Http\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\MembersTable $Members
 */
class DownloadPhotosCommand extends Command
{
    use CurrentMatchdayTrait;

    public \App\Model\Table\SeasonsTable $Seasons;
    public \App\Model\Table\MatchdaysTable $Matchdays;
    public \App\Model\Table\MembersTable $Members;

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
        $this->Seasons = $this->fetchTableClass(SeasonsTable::class);
        $this->Matchdays = $this->fetchTableClass(MatchdaysTable::class);
        $this->Members = $this->fetchTableClass(MembersTable::class);
        $this->getCurrentMatchday();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $io->out('Download photos task');
        $baseUrl = 'www.guido8975.it';
        $url = '/index.php?ctg=15';
        $referer = 'http://' . $baseUrl . $url;

        $path = IMG_PLAYERS . 'season-new' . DS;

        /** @var \App\Model\Entity\Member[] $members */
        $members = $this->Members->find()
            ->contain(['Players'])
            ->where(['season_id' => $this->currentSeason->id])->all();
        foreach ($members as $member) {
            $client = new Client(['host' => $baseUrl, 'headers' => ['Referer' => $referer]]);
            $io->out('Searching user ' . $member->player->full_name);
            $response = $client->post($url, ['PanCal' => $member->player->full_name]);
            if ($response->isOk()) {
                $response->getCookie('PHPSESSID');
                foreach ($response->getHeaders() as $name => $values) {
                    $io->out($name . ': ' . implode(', ', $values));
                }

                //$this->out($response->getStringBody());
                $crawler = new Crawler();
                $crawler->addContent($response->getStringBody());
                $trs = $crawler->filter('table.Result tr a');
                $io->out('Trovati ' . $trs->count());
                if ($trs->count() >= 1) {
                    $trs->first();
                    $href = $trs->attr('href');
                    if ($href != null) {
                        $io->out('Found ' . $href);
                        $href = 'http://' . $baseUrl . '/' . $href;
                        $io->out('Url ' . $href);
                        $client = new Client();
                        $response = $client->get($href, [], ['headers' => ['Referer' => $referer]]);
                        //$this->out($response->getStringBody());
                        if ($response->isOk()) {
                            $file = $path . (string)$member->code_gazzetta . '.jpg';
                            $io->out('Savings ' . '/' . $href . ' => ' . $file);
                            file_put_contents($file, $response->getStringBody());
                        }
                    }
                }
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
