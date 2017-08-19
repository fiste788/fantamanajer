<?php
namespace App\Shell\Task;

use App\Model\Entity\Matchday;
use App\Model\Entity\Member;
use App\Model\Entity\Season;
use App\Model\Table\ClubsTable;
use App\Model\Table\MatchdaysTable;
use App\Model\Table\MembersTable;
use App\Model\Table\PlayersTable;
use App\Model\Table\RolesTable;
use Cake\Console\Shell;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Http\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property MatchdaysTable $Matchdays
 * @property RolesTable $Roles
 * @property MembersTable $Members
 * @property ClubsTable $Clubs
 * @property PlayersTable $Players
 * @property Matchday $currentMatchday
 */
class GazzettaTask extends Shell
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
        $this->loadModel('Roles');
        $this->loadModel('Members');
        $this->loadModel('Clubs');
        $this->loadModel('Players');
        $this->currentMatchday = $this->Matchdays->findCurrent();
        $this->currentSeason = $this->currentMatchday->season;
    }
    
    public function main()
    {
        $this->out('Gazzetta task');
    }
    
    public function getRatings($matchday = null, $offsetGazzetta = 0, $forceDownload = false) {
        $year = $this->currentSeason->year;
        $matchday = $matchday ? $matchday : $this->currentMatchday->number;
        $folder = new Folder(RATINGS_CSV . $year, true);
        $pathCsv = $folder->path . DS . "Giornata" . str_pad($matchday, 2, "0", STR_PAD_LEFT) . ".csv";
        $file = new File($pathCsv);
        $this->out("Search file in path " . $file->path);
        if($file->exists() && $file->size() > 0 && !$forceDownload) {
            return $pathCsv;
        } else {
            return $this->downloadRatings($pathCsv, ($matchday + $offsetGazzetta));
        }
    }
    
    private function downloadRatings($path, $matchdayGazzetta) {
        $url = $this->getRatingsFileUrl($matchdayGazzetta);
        if (!empty($url)) {
            $content = $this->decryptMXMFile($url);
            if (!empty($content)) {
                $this->writeCsvRatings($content, $path);
                //self::writeXmlVoti($content, $percorsoXml);
                return $path;
            }
        }
    }
    
    protected static function writeCsvRatings($content, $path) {
        $pieces = explode("\n", $content);
        array_pop($pieces);
        foreach ($pieces as $key => $val) {
            $pieces = explode("|", $val);
            $pieces[$key] = join(";", $pieces);
            if ($pieces[4] == 0) {
                unset($pieces[$key]);
            }
        }
        $this->createFile($path, join("\n", $pieces));
    }
    
    /**
     * 
     * @param string $url
     * @return string
     */
    public function decryptMXMFile($url = null) {
        $stringa = "";
        $this->out("Starting decrypt " . $url);
        $currentMachday = $this->Matchdays->findCurrent();
        $decrypt = $currentMachday->get('season')->get('key_gazzetta');
        $this->out($decrypt);
        if ($url && $p_file = fopen($url, "r")) {    
			$explode_xor = explode("-", $decrypt);
            $i = 0;
            $votiContent = file_get_contents($url);
			if (!empty($votiContent)) {
                while (!feof($p_file)) {
                    if ($i == count($explode_xor)) {
                        $i = 0;
                    }
                    $linea = fgets($p_file, 2);
                    $xor2 = hexdec(bin2hex($linea)) ^ hexdec($explode_xor[$i]);
                    $i++;
                    $stringa .= chr($xor2);
                }
            }
            fclose($p_file);
        }
        return $stringa;
    }
    
    public function getRatingsFileUrl($matchday = null) {
        $this->out("Search ratings on maxigames");
        $http = new Client();
        $response = $http->get("http://maxigames.maxisoft.it/downloads.php");
        if ($response->isOk()) {
            $this->out("Maxigames found");
            $crawler = new Crawler();
            $crawler->addContent($response->body());
            $td = $crawler->filter("#content td:contains('Giornata $matchday')");
            if($td->count() > 0) {
                $url = $td->nextAll()->filter("a")->attr("href");
                $response = $http->get($url);
                if ($response->isOk()) {
                    $crawler = new Crawler();
                    $crawler->addContent($response->body());
                    $url = $crawler->filter("#default_content_download_button")->attr("href");
                    $this->out($url);
                    return $url;
                }
            }
        } else {
            $this->abort("Could not connect to Maxigames");
        }
    }
    
    public function updateMembers(Season $season = null, $path = null) {
        $this->out('Updating members of matchday ' . $this->currentMatchday->number);
        $season = $season ? $season : $this->currentSeason;
        $query = $this->Members->find('list', [
            'keyField' => 'id',
            'valueField' => function ($obj) {
                return $obj;
            }
        ])->where(['season_id' => $season->id]);
        $oldMembers = $query->toArray();
        $path = $path ? $path : $this->getRatings($this->currentMatchday->number);
        $newMembers = $this->returnArray($path, ";");
        
        //$this->out($rolesById);
        $membersToSave = [];
        foreach ($newMembers as $id => $newMember) {
            if (array_key_exists($id, $oldMembers)) {
                $membersToSave[] = $this->memberTransfert($oldMembers[$id], $newMember[3]);
            } else {
                $membersToSave[] = $this->memberNew($newMember);
            }
        }
        foreach ($oldMembers as $id => $oldMember) {
            if (!array_key_exists($id, $newMembers) && $oldMember->active) {
                $oldMember->active = FALSE;
                $membersToSave[] = $oldMember;
                $this->out("Deactivate member " . $oldMember);
                //$oldMember->save(array('numEvento'=>Event::RIMOSSOGIOCATORE));
            }
        }
        //$this->Members->saveMany($membersToSave);
        return TRUE;
    }
    
    private function memberTransfert(Member $member, $club) {
        $clubNew = $this->Clubs->fingByName(ucwords(strtolower(trim($club, '"'))));
        if ($member->clubId != $clubNew->id) {
            $member->club = $clubNew;
            $member->active = TRUE;
            return $member;
        }
    }
    
    private function memberNew($member) {
        $esprex = "/[A-Z']*\s?[A-Z']{2,}/";
        $fullname = trim($member[2], '"');
        $ass = NULL;
        preg_match($esprex, $fullname, $ass);
        $surname = ucwords(strtolower(((!empty($ass)) ? $ass[0] : $fullname)));
        $name = ucwords(strtolower(trim(substr($fullname, strlen($surname)))));
        $search = $this->Players->find()->where([
            'surname' => $surname,
            'name' => $name
        ]);
        $this->out("Add new member " . $surname . " " . $name);
        return $this->Members->newEntities([
            'code_gazzetta' => $member[0],
            'role_id' => $this->Roles->get($member[5]),
            'club_id' => $this->Clubs->findByName(trim($member[3], '"')),
            'active' => true,
            'player' => $this->Players->findOrCreate($search,null,['atomic' => false])
        ]);
    }
    
    public function importRatings($matchday = null, $path = null) {
        $this->loadModel("Ratings");
        $currentMatchday = $this->Matchdays->findCurrent();
        $matchday = $matchday ? $matchday : $currentMatchday->number;
        $path = $path ? $path : $this->getRatings($matchday);
        $members = $this->returnArray($path, ";");
        foreach ($members as $id => $stats) {
            $ratings[] = $this->Ratings->newEntities([
                'valued' => $stats[6],
                'points' => $stats[7],
                'rating' => $stats[10],
                'goals' => $stats[11],
                'goals_against' => $stats[12],
                'goals_victory' => $stats[13],
                'goals_tie' => $stats[14],
                'assist' => $stats[15],
                'yellow_card' => $stats[16],
                'red_card' => $stats[17],
                'penalities_scores' => $stats[18],
                'penalities_taken' => $stats[19],
                'present' => $stats[23],
                'regular' => $stats[24],
                'quotation' => $stats[27]
            ]);
        }
        return $this->Ratings->saveMany($ratings);
    }

    public function returnArray($path, $sep = ";", $header = FALSE) {
        $content = trim(file_get_contents($path));
        $array = explode("\n", $content);
        if ($header) { 
            array_shift($header);
        }
        foreach ($array as $val) {
            $par = explode($sep, $val);
            $array = trim($val);
            $arrayOk[$par[0]] = $par;
        }
        return $arrayOk;
    }


    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('get_ratings_file_url', [
            'help' => 'Get the url of the ratings file'
        ]);
        $parser->addSubcommand('get_ratings', [
            'help' => 'Download file ratings if not exist'
        ]);
        $parser->addSubcommand('update_members');
        $parser->addSubcommand('import_ratings');
        return $parser;
    }
}