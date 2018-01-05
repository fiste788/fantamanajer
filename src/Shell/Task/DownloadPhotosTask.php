<?php
namespace App\Shell\Task;

use App\Model\Table\MatchdaysTable;
use App\Model\Table\MembersTable;
use App\Model\Table\SeasonsTable;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Http\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\MembersTable $Members
 */
class DownloadPhotosTask extends Shell
{

    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
        $this->loadModel('Members');
        $this->getCurrentMatchday();
    }

    public function main()
    {
        $this->out('Download photos task');
        $baseUrl = "www.guido8975.it";
        $url = "/index.php?ctg=15";
        $referer = "http://" . $baseUrl . $url;
                        $this->out("REFEREr " . $referer);

        $path = Configure::read('App.paths.images.players') . 'season-new' . DS;
        $members = $this->Members->find()->contain(['Players'])->where(['season_id' => $this->currentSeason->id])->all();
        foreach ($members as $member) {
            $client = new Client(['host' => $baseUrl, 'headers' => ['Referer' => $referer]]);
            $this->out("Searching user " . $member->player->full_name);
            $response = $client->post($url, ['PanCal' => $member->player->full_name]);
            if ($response->isOk()) {
                $cookies = $response->getCookie("PHPSESSID");
                foreach ($response->getHeaders() as $name => $values) {
                    $this->out($name . ": " . implode(", ", $values));
                }

                //$this->out($response->body());
                $crawler = new Crawler();
                $crawler->addContent($response->body());
                $trs = $crawler->filter("table.Result tr a");
                $this->out("Trovati " . $trs->count());
                if ($trs->count() >= 1) {
                    $tr = $trs->first();
                    $href = $trs->attr("href");
                    $this->out("Found " . $href);
                    if ($href != "") {
                        $href = "http://" . $baseUrl . '/' . $href;
                        $this->out("Url " . $href);
                        $client = new Client();
                        $response = $client->get($href, [], ['headers' => ['Referer' => $referer]]);
                        //$this->out($response->body());
                        if ($response->isOk()) {
                            $this->out("Savings " . '/' . $href . " => " . $path . $member->code_gazzetta . '.jpg');
                            file_put_contents($path . $member->code_gazzetta . '.jpg', $response->body());
                        }
                    }
                }
            }
        }
    }
}
