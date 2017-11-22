<?php
namespace App\Shell\Task;

use App\Model\Entity\Season;
use App\Model\Table\MatchdaysTable;
use App\Model\Table\SeasonsTable;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Shell;
use Cake\Http\Client;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property MatchdaysTable $Matchdays
 * @property SeasonsTable $Seasons
 */
class MatchdayTask extends Shell
{
    use CurrentMatchdayTrait;
    
    public function initialize() {
        parent::initialize();
        $this->loadModel('Matchdays');
        $this->loadModel('Seasons');
        $this->getCurrentMatchday();
    }
    
    public function main()
    {
        $this->out('Matchday task');
    }
    
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('get_matchday_schedule', [
            'help' => 'Get the date of the given matchday from lega seria A'
        ]);
        $parser->addSubcommand('update_matchday');
        $parser->addSubcommand('update_calendar');
        return $parser;
    }
    
    /**
     * 
     * @param type $matchday
     * @return \DateTime | null
     */
    public function getMatchdaySchedule($matchday = null, $season = null) {
        if($matchday == null) {
            $matchday = $this->currentMatchday->number;
        }
        if($season == null) {
            $season = $this->currentSeason;
        }
        $year = $season->year . "-" . substr($season->year + 1, 2, 2);
        $url = "/it/serie-a-tim/calendario-e-risultati/$year/UNICO/UNI/$matchday";
        $client = new Client([
            'host' => 'www.legaseriea.it',
            'redirect' => 5
        ]);
        $this->verbose("Downloading page " . $url);
        $response = $client->get($url);
        if ($response->isRedirect()) {
            $response = $client->get($response->getHeaderLine('Location'));
        }
        if ($response->isOk()) {
            $crawler = new Crawler();
            $crawler->addContent($response->body());
            $box = $crawler->filter(".datipartita")->first()->filter("p")->first()->filter("span");
            $date = $box->text();
            if ($date != "") {
                //$this->out($date);
                return DateTime::createFromFormat("!d/m/Y H:i", $date);
            } else {
                $this->abort("Cannot find .datipartita");
            }
        } else {
            $this->err($response->getStatusCode(), 1);
            $this->abort("Cannot connect to " . $url);
        }
    }
    
    public function updateMatchday($matchdayNumber = null, $season = null, $interactive = true) {
        if($season == null) {
            $season = $this->currentSeason;
        }
        if($matchdayNumber == null) {
            $matchday = $this->currentMatchday;
            $matchdayNumber = $this->currentMatchday->number;
        } else {
            $matchday = $this->Matchdays->findByNumberAndSeasonId($matchdayNumber,$season->id)->first();
            if(is_null($matchday)) {
                $matchday = $this->Matchdays->newEntity();
                $matchday->season_id = $season->id;
                $matchday->number = $matchdayNumber;
            }
        }
        $date = $this->getMatchdaySchedule($matchdayNumber, $season);
        if($date) {
            $res = !$interactive || ($interactive && $this->in("Set " . $date->format("Y-m-d H:i:s") . " for matchday " . $matchdayNumber, ['s','n'], 's') == 's');
            if($res) {
                $matchday->set('date', $date);
                $this->Matchdays->save($matchday);
            }
        }
    }
    
    public function updateCalendar($season = null) {
        if($season == null) {
            $season = $this->currentSeason;
        }
        //$matchdays = $this->Matchdays->findBySeasonId($this->currentSeason->id);
        $matchdays = 38;
        $progress = $this->helper('Progress');
        $progress->init([
            'total' => $matchdays
        ]);
        for ($matchday = 1; $matchday <= $matchdays; $matchday++) {
            $progress->increment();
            $progress->draw();
            $this->updateMatchday($matchday, $season, false);
        }
    }
    
    /**
     * 
     * @return Season
     */
    public function startNewSeason() {
        $year = date("Y");
        $season = $this->Seasons->find()->where(['year' => $year])->first();
        if($season == null) {
            $season = $this->Seasons->newEntity([
                'year' => $year,
                'name' => 'Stagione ' . $year . '/' . substr($year + 1, 2, 2)
            ]);
            $this->Seasons->saveOrFail($season);
            $this->out('Created new season for year ' . $year);
            
            $firstMatchday = $this->Matchdays->newEntity([
                'season_id' => $season->id,
                'number' => 0,
                'date' => new \DateTime($year . '-08-10 00:00:00')
            ]);
            $this->Matchdays->save($firstMatchday);
            $this->out('Updating calendar');
            $this->updateCalendar($season);
            $this->out('Creating last matchday');
            $lastMatchday = $this->Matchdays->newEntity([
                'season_id' => $season->id,
                'number' => 39,
                'date' => new \DateTime($year + 1 . '-07-31 23:59:59')
            ]);
            if($this->Matchdays->save($lastMatchday)) {
                return $season;
            }
        } else {
            return $season;
        }
    }
}
