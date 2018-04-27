<?php
namespace App\Shell\Task;

use App\Model\Entity\Member;
use App\Model\Entity\Season;
use App\Model\Table\RatingsTable;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Shell;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Http\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\RolesTable $Roles
 * @property \App\Model\Table\MembersTable $Members
 * @property \App\Model\Table\ClubsTable $Clubs
 * @property \App\Model\Table\PlayersTable $Players
 * @property RatingsTable $Ratings
 */
class GazzettaTask extends Shell
{
    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
        $this->loadModel('Roles');
        $this->loadModel('Members');
        $this->loadModel('Clubs');
        $this->loadModel('Players');
        $this->loadModel("Ratings");
        $this->getCurrentMatchday();
    }

    public function main()
    {
        $this->out('Gazzetta task');
    }

    public function startup()
    {
        parent::startup();
        if ($this->param('no-interaction')) {
            $this->interactive = false;
        }
    }

    public function getRatings($matchday = null, $offsetGazzetta = 0, $forceDownload = false)
    {
        if ($matchday === null) {
            $matchday = $this->currentMatchday->number;
        }
        $year = $this->currentSeason->year;
        $folder = new Folder(RATINGS_CSV . $year, true);
        $pathCsv = $folder->path . DS . "Giornata" . str_pad($matchday, 2, "0", STR_PAD_LEFT) . ".csv";
        $file = new File($pathCsv);
        $this->out("Search file in path " . $file->path);
        if ($file->exists() && $file->size() > 0 && !$forceDownload) {
            return $pathCsv;
        } else {
            return $this->downloadRatings($pathCsv, ($matchday + $offsetGazzetta));
        }
    }

    private function downloadRatings($path, $matchdayGazzetta)
    {
        $url = $this->getRatingsFile($matchdayGazzetta);
        if (!empty($url)) {
            $content = $this->decryptMXMFile($url);
            if (!empty($content)) {
                $this->writeCsvRatings($content, $path);
                //self::writeXmlVoti($content, $percorsoXml);
                return $path;
            }
        }
    }

    public function writeCsvRatings($content, $path)
    {
        $lines = explode("\n", $content);
        array_pop($lines);
        foreach ($lines as $key => $val) {
            $pieces = explode("|", $val);
            $lines[$key] = join(";", $pieces);
            if ($pieces[4] == 0) {
                unset($lines[$key]);
            }
        }
        $this->createFile($path, join("\n", $lines));
    }

    /**
     *
     * @param string $path
     * @return string
     */
    public function decryptMXMFile($path = null)
    {
        $body = "";
        $this->out("Starting decrypt " . $path);
        $currentMachday = $this->Matchdays->find('current');
        $decrypt = $currentMachday->first()->get('season')->get('key_gazzetta');
        $this->out($decrypt);
        if ($path && $p_file = fopen($path, "r")) {
            $explode_xor = explode("-", $decrypt);
            $i = 0;
            $content = file_get_contents($path);
            if (!empty($content)) {
                while (!feof($p_file)) {
                    if ($i == count($explode_xor)) {
                        $i = 0;
                    }
                    $line = fgets($p_file, 2);
                    $xor2 = hexdec(bin2hex($line)) ^ hexdec($explode_xor[$i]);
                    $i++;
                    $body .= chr($xor2);
                }
            }
            fclose($p_file);
        }

        return $body;
    }

    public function getRatingsFile($matchday = null)
    {
        $this->out("Search ratings on maxigames");
        $http = new Client();
        $http->setConfig('ssl_verify_peer', false);
        $url = "https://maxigames.maxisoft.it/downloads.php";
        $this->verbose("Downloading " . $url);
        $response = $http->get($url);
        if ($response->isOk()) {
            $this->out("Maxigames found");
            $crawler = new Crawler();
            $crawler->addContent($response->body());
            $td = $crawler->filter("#content td:contains('Giornata $matchday')");
            if ($td->count() > 0) {
                $url = $td->nextAll()->filter("a")->attr("href");
                $this->verbose("Downloading " . $url);
                $response = $http->get($url);
                if ($response->isOk()) {
                    $crawler = new Crawler();
                    $crawler->addContent($response->body());
                    $button = $crawler->filter("#default_content_download_button");
                    if ($button->count()) {
                        $url = $button->attr("href");
                    } else {
                        $url = str_replace("www", "dl", $url);
                    }
                    $this->out("Downloading $url in tmp dir");
                    $file = TMP . $matchday . '.mxm';
                    file_put_contents($file, file_get_contents($url));

                    return $file;
                }
            }
        } else {
            $this->abort("Could not connect to Maxigames");
        }
    }

    public function updateMembers(Season $season = null, $matchdayNumber = null, $path = null)
    {
        $this->out('Updating members of matchday ' . $matchdayNumber);
        if ($season == null) {
            $season = $this->currentSeason;
        }
        if ($matchdayNumber === null) {
            $matchdayNumber = $this->currentMatchday->number;
        }
        while ($path == null && $matchdayNumber > 0) {
            $path = $this->getRatings($matchdayNumber);
            $matchdayNumber--;
        }
        if (file_exists($path)) {
            $query = $this->Members->find(
                'list',
                [
                'keyField' => 'code_gazzetta',
                'valueField' => function ($obj) {
                    return $obj;
                },
                'contain' => ['Players']
                ]
            )->where(['season_id' => $season->id]);
            $oldMembers = $query->toArray();
            $newMembers = $this->returnArray($path, ";");
            //$this->abort(print_r($oldMembers,1));
            //$this->out($rolesById);
            $membersToSave = [];
            foreach ($newMembers as $id => $newMember) {
                $member = null;
                if (array_key_exists($id, $oldMembers)) {
                    $member = $this->memberTransfert($oldMembers[$id], $newMember[3]);
                } else {
                    $member = $this->memberNew($newMember, $season);
                }
                if ($member != null) {
                    $membersToSave[] = $member;
                }
            }
            foreach ($oldMembers as $id => $oldMember) {
                if (!array_key_exists($id, $newMembers) && $oldMember->active) {
                    $oldMember->active = false;
                    $membersToSave[] = $oldMember;
                    $this->verbose("Deactivate member " . $oldMember);
                    //$oldMember->save(array('numEvento'=>Event::RIMOSSOGIOCATORE));
                }
            }
            //$this->verbose($membersToSave);
            $this->out("Savings " . count($membersToSave) . " members");
            if (!$this->Members->saveMany($membersToSave)) {
                foreach ($membersToSave as $value) {
                    if (!empty($value->getErrors())) {
                        $this->err($value);
                        $this->err(print_r($value->getErrors()));
                    }
                }
            }
        } else {
            $this->abort('Cannot download ratings file');
        }
    }

    private function memberTransfert(Member $member, $club)
    {
        $flag = false;
        if (!$member->active) {
            $member->active = true;
            $flag = true;
        }
        $clubNew = $this->Clubs->findByName(ucwords(strtolower(trim($club, '"'))))->first();
        if ($member->club_id != $clubNew->id) {
            $this->verbose("Transfert member " . $member->player->fullName);
            $member->club = $clubNew;
            $member->active = true;
            $flag = true;
        }
        if ($flag) {
            return $member;
        }
    }

    private function memberNew($member, $season)
    {
        $esprex = "/[A-Z']*\s?[A-Z']{2,}/";
        $fullname = trim($member[2], '"');
        $ass = null;
        preg_match($esprex, $fullname, $ass);
        $surname = ucwords(strtolower(((!empty($ass)) ? $ass[0] : $fullname)));
        $name = ucwords(strtolower(trim(substr($fullname, strlen($surname)))));
        //$queryPlayer = $this->Players->find()->where();
        $player = $this->Players->findOrCreate(
            [
            'surname' => $surname,
            'name' => $name
            ],
            null,
            ['atomic' => false]
        );
        //$queryClub = $this->Clubs->findByName();
        $club = $this->Clubs->findOrCreate(['name' => ucwords(strtolower(trim($member[3], '"')))], null, ['atomic' => false]);
        $this->verbose("Add new member " . $surname . " " . $name);

        return $this->Members->newEntity(
            [
            'season_id' => $season->id,
            'code_gazzetta' => $member[0],
            'playmaker' => $member[26],
            'active' => 1,
            'role_id' => $member[5] + 1,
            'club_id' => $club->id,
            'player_id' => $player->id
            ]
        );
    }

    public function importRatings($matchdayNumber = null, $path = null)
    {
        if ($matchdayNumber === null) {
            $matchday = $this->currentMatchday;
            $matchdayNumber = $this->currentMatchday->number;
        } else {
            $matchday = $this->Matchdays->findByNumberAndSeasonId($matchdayNumber, $this->currentSeason->id)->first();
        }
        $path = $path ? $path : $this->getRatings($matchdayNumber);
        if ($path) {
            $csvRow = $this->returnArray($path, ";");
            $members = $this->Members->findListBySeasonId($matchday->season_id)
                ->contain(['Roles', 'Ratings' => function (\Cake\ORM\Query $q) use ($matchday) {
                    return $q->where(['matchday_id' => $matchday->id]);
                }])->toArray();

            foreach ($csvRow as $stats) {
                if (array_key_exists($stats[0], $members)) {
                    $member = $members[$stats[0]];
                    if (empty($member->ratings)) {
                        $rating = $this->Ratings->newEntity();
                    } else {
                        $rating = $member->ratings[0];
                    }
                    $rating->member = $member;
                    $this->Ratings->patchEntity(
                        $rating,
                        [
                        //$rating = $this->Ratings->newEntity([
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
                        'penalities_scored' => $stats[18],
                        'penalities_taken' => $stats[19],
                        'present' => $stats[23],
                        'regular' => $stats[24],
                        'quotation' => (int)$stats[27],
                        'member_id' => $member->id,
                        'matchday_id' => $matchday->id
                        ]
                    );
                    $rating->points_no_bonus = $this->currentSeason->bonus_points ? $rating->calcNoBonusPoints() : $rating->points;
                    $ratings[] = $rating;
                } else {
                    throw new RecordNotFoundException("No member for code_gazzetta $stats[0]");
                }
            }

            if (!$this->Ratings->saveMany($ratings, [
                'checkExisting' => false,
                'associated' => false,
                'checkRules' => false
                ])) {
                foreach ($ratings as $value) {
                    if (!empty($value->getErrors())) {
                        $this->err($value);
                        $this->err(print_r($value->getErrors()));
                    }
                }

                return false;
            }

            return true;
        }
    }

    public function returnArray($path, $sep = ";", $header = false)
    {
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

    public function calculateKey($encryptedFilePath = null, $dectyptedFilePath = null)
    {
        $this->out('Calculating decrypting key');
        if (is_null($encryptedFilePath)) {
            $encryptedFilePath = RATINGS_CSV . $this->currentSeason->year . DS . "mcc00.mxm";
        }
        if (is_null($dectyptedFilePath)) {
            $dectyptedFilePath = TMP . "0.txt";
        }
        if (!file_exists($encryptedFilePath)) {
            $encryptedFilePath = $this->getRatingsFile(0);
        }
        $reply = 'y';
        if (!file_exists($dectyptedFilePath)) {
            if ($this->interactive) {
                $reply = $this->in('Copy decrypted file in ' . $dectyptedFilePath . ' and then press enter. If you don\'t have one go to http://fantavoti.francesco-pompili.it/Decript.aspx', ['y', 'n'], 'y');
            }
        }
        if ($reply == 'y') {
            $decript = file_get_contents($dectyptedFilePath);
            $encript = file_get_contents($encryptedFilePath);
            $res = "";
            for ($i = 0; $i < 28; $i++) {
                $xor1 = hexdec(bin2hex($decript[$i]));
                $xor2 = hexdec(bin2hex($encript[$i]));
                if ($i != 0) {
                    $res .= '-';
                }
                $res .= dechex($xor1 ^ $xor2);
            }
            $this->out('Key: ' . $res);
            $this->currentSeason->key_gazzetta = $res;
            if ($this->Seasons->save($this->currentSeason)) {
                copy($dectyptedFilePath, $dectyptedFilePath . "." . $this->currentSeason->year . ".bak");
                unlink($dectyptedFilePath);

                return $res;
            }
        }
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand(
            'get_ratings_file_url',
            [
            'help' => 'Get the url of the ratings file'
            ]
        );
        $parser->addSubcommand(
            'get_ratings',
            [
            'help' => 'Download file ratings if not exist'
            ]
        );
        $parser->addSubcommand('update_members');
        $parser->addSubcommand('import_ratings');
        $parser->addSubcommand('fix_points');
        $parser->addSubcommand(
            'calculate_key',
            [
            'help' => 'Calculate and save the key for decrypting gazzetta file'
            ]
        );
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true,
            'default' => false
        ]);

        return $parser;
    }

    public function fixPoints()
    {
        $this->out('Fix Points');
        $this->Seasons->loadInto($this->currentSeason, ['Matchdays.Ratings.Members.Roles']);
        foreach ($this->currentSeason->matchdays as $matchday) {
            $ratings = [];
            foreach ($matchday->ratings as $rating) {
                $rating->points_no_bonus = $this->currentSeason->bonus_points ? $rating->calcNoBonusPoints() : $rating->points;
                $ratings[] = $rating;
            }
            $this->Ratings->saveMany($ratings);
        }
    }
}
