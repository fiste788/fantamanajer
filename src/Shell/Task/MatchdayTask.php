<?php
namespace App\Shell\Task;

use App\Model\Table\MatchdaysTable;
use Cake\Console\Shell;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property MatchdaysTable $Matchdays
 */
class MatchdayTask extends Shell
{
    /**
     *
     * @var \App\Model\Entity\Matchday
     */
    private $currentMatchday = null;
    
    /**
     *
     * @var \App\Model\Entity\Season
     */
    private $currentSeason = null;
    
    public function initialize() {
        parent::initialize();
        $this->loadModel('Matchdays');
        $this->currentMatchday = $this->Matchdays->findCurrent();
        $this->currentSeason = $this->currentMatchday->season;
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
     * @return Datetime | null
     */
    public function getMatchdaySchedule($matchday = null) {
        $matchday = $matchday ? $matchday : $this->currentMatchday->number;
        $year = $this->currentSeason->year . "-" . substr($this->currentSeason->year + 1, 2, 2);
        $url = "http://www.legaseriea.it/it/serie-a-tim/calendario-e-risultati/$year/UNICO/UNI/$matchday";
        $client = new \Cake\Http\Client();
        $this->verbose("Downloading page " . $url);
        $response = $client->get($url);
        if ($response->isOk()) {
            $crawler = new Crawler();
            $crawler->addContent($response->body());
            $box = $crawler->filter(".datipartita")->first()->filter("p")->first()->filter("span");
            $date = $box->text();
            if ($date != "") {
                $this->out($date);
                return DateTime::createFromFormat("!d/m/Y H:i", $date);
            } else {
                $this->abort("Cannot find .datipartita");
            }
        } else {
            $this->abort("Cannot connect to " . $url);
        }
    }
    
    public function updateMatchday($matchdayNumber = null) {
        if($matchdayNumber == null) {
            $matchday = $this->currentMatchday;
            $matchdayNumber = $this->currentMatchday->number;
        } else {
            $matchday = $this->Matchdays->findByNumberAndSeason($matchdayNumber,$this->currentSeason);
        }
        $date = $this->getMatchdaySchedule($matchdayNumber);
        if($date) {
            if($this->in("Set " . $date . " for matchday " . $matchdayNumber, [true,false], true)) {
                $matchday->set('data', $date);
                $this->Matchdays->save($matchday);
            }
        }
    }
    
    public function updateCalendar() {
        $matchdays = $this->Matchdays->findBySeasonId($this->currentSeason->id);
        $progress = $this->helper('Progress');
        $progress->init([
            'total' => $matchdays->count()
        ]);
        foreach ($matchdays as $matchday) {
            $progress->increment();
            $progress->draw();
            if ($matchday->number < 39) {
                $this->updateMatchday($matchday->number);
            }
            
        }
    }
}
