<?php
namespace App\Command\Traits;

use App\Model\Entity\Matchday;
use App\Model\Entity\Member;
use App\Model\Entity\Season;
use App\Model\Table\RatingsTable;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Http\Client;
use Cake\ORM\Query;
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
trait GazzettaTrait
{
    /**
     *
     * @var ConsoleIo
     */
    private $io;

    /**
     *
     * @var Arguments
     */
    private $args;

    public function startup(Arguments $args, ConsoleIo $io)
    {
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
        $this->loadModel('Roles');
        $this->loadModel('Members');
        $this->loadModel('Clubs');
        $this->loadModel('Players');
        $this->loadModel("Ratings");
        $this->args = $args;
        $this->io = $io;
    }

    public function getRatings(Matchday $matchday, $offsetGazzetta = 0, $forceDownload = false)
    {
        $year = $matchday->season->year;
        $folder = new Folder(RATINGS_CSV . $year, true);
        $pathCsv = $folder->path . DS . "Matchday" . str_pad($matchday->number, 2, "0", STR_PAD_LEFT) . ".csv";
        $file = new File($pathCsv);
        $this->io->out("Search file in path " . $file->path);
        if ($file->exists() && $file->size() > 0 && !$forceDownload) {
            return $pathCsv;
        } else {
            return $this->downloadRatings($matchday, $pathCsv, ($matchday->number + $offsetGazzetta));
        }
    }

    private function downloadRatings(Matchday $matchday, $path, $matchdayGazzetta)
    {
        $url = $this->getRatingsFile($matchdayGazzetta);
        if (!empty($url)) {
            $content = $this->decryptMXMFile($matchday, $url);
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
        $this->io->createFile($path, join("\n", $lines));
    }

    /**
     *
     * @param string $matchday
     * @return string
     */
    public function decryptMXMFile(Matchday $matchday, $path = null)
    {
        $body = "";
        $this->io->out("Starting decrypt " . $path);
        $decrypt = $matchday->season->key_gazzetta;
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
        $this->io->out("Search ratings on maxigames");
        $http = new Client();
        $http->setConfig('ssl_verify_peer', false);
        $url = "https://maxigames.maxisoft.it/downloads.php";
        $this->io->verbose("Downloading " . $url);
        $response = $http->get($url);
        if ($response->isOk()) {
            $this->io->out("Maxigames found");
            $crawler = new Crawler();
            $crawler->addContent($response->body());
            $tr = $crawler->filter(".container .col-sm-9 tr:contains('Giornata $matchday')");
            if ($tr->count() > 0) {
                $this->io->out("Matchday found");
                $url = $this->getUrlFromMatchdayRow($tr);

                return $this->downloadDropboxUrl($url, $matchday, $http);
            }
        }
    }

    private function getUrlFromMatchdayRow($tr)
    {
        $button = $tr->selectButton("DOWNLOAD");
        if (!$button->count()) {
            $link = $tr->selectLink("DOWNLOAD");
            if ($link->count()) {
                return $link->link()->getUri();
            }
        } else {
            $matches = sscanf($button->attr("onclick"), "window.open('%[^']");
            //preg_match("/window.open\(\'(.*?)\'#is/",,$matches);
            return $matches[0];
        }
    }

    private function downloadDropboxUrl($url, $matchday, $http)
    {
        if ($url) {
            $this->io->verbose("Downloading " . $url);
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
                $this->io->out("Downloading $url in tmp dir");
                $file = TMP . $matchday . '.mxm';
                file_put_contents($file, file_get_contents($url));

                return $file;
            }
        }
    }

    public function updateMembers(Matchday $matchday, $path = null)
    {
        $matchdayNumber = $matchday->number;
        $this->io->out('Updating members of matchday ' . $matchdayNumber);
        while ($path == null && $matchdayNumber > -1) {
            $matchday = $this->Matchdays->find()->contain(['Seasons'])->where([
                'number' => $matchdayNumber,
                'season_id' => $matchday->season_id
            ])->first();
            $path = $this->getRatings($matchday);
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
            )->where(['season_id' => $matchday->season->id]);
            $oldMembers = $query->toArray();
            $newMembers = $this->returnArray($path, ";");
            //$this->abort(print_r($oldMembers,1));
            //$this->io->out($rolesById);
            $buys = [];
            $sells = [];
            $membersToSave = [];
            foreach ($newMembers as $id => $newMember) {
                $member = null;
                if (array_key_exists($id, $oldMembers)) {
                    $member = $this->memberTransfert($oldMembers[$id], $newMember[3]);
                    if ($member != null) {
                        $buys[$member->club_id][] = $member;
                        if ($member->isDirty('club_id')) {
                            $sells[$member->getOriginal('club_id')][] = $member;
                        }
                    }
                } else {
                    $member = $this->memberNew($newMember, $matchday->season);
                    $buys[$member->club_id][] = $member;
                }
                if ($member != null) {
                    $membersToSave[] = $member;
                }
            }
            foreach ($oldMembers as $id => $oldMember) {
                if (!array_key_exists($id, $newMembers) && $oldMember->active) {
                    $oldMember->active = false;
                    $membersToSave[] = $oldMember;
                    $this->io->verbose("Deactivate member " . $oldMember);
                    $sells[$member->club_id][] = $member;
                }
            }
            //$this->io->verbose($membersToSave);
            $this->io->out("Savings " . count($membersToSave) . " members");
            if (!$this->Members->saveMany($membersToSave)) {
                $ev = new \Cake\Event\Event('Fantamanajer.memberTransferts', $this, [
                    'sells' => $sells,
                    'buys' => $buys
                ]);
                \Cake\Event\EventManager::instance()->dispatch($ev);
                foreach ($membersToSave as $value) {
                    if (!empty($value->getErrors())) {
                        $this->io->err($value);
                        $this->io->err(print_r($value->getErrors()));
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
            $this->io->verbose("Transfert member " . $member->player->fullName);
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
        $this->io->verbose("Add new member " . $surname . " " . $name);

        return $this->Members->newEntity(
            [
            'season_id' => $season->id,
            'code_gazzetta' => $member[0],
            'playmaker' => $member[26],
            'active' => true,
            'role_id' => $member[5] + 1,
            'club_id' => $club->id,
            'player_id' => $player->id
            ]
        );
    }

    public function importRatings(Matchday $matchday, $path = null)
    {
        $path = $path ? $path : $this->getRatings($matchday);
        if ($path) {
            $csvRow = $this->returnArray($path, ";");
            $members = $this->Members->findListBySeasonId($matchday->season_id)
                ->contain(['Roles', 'Ratings' => function (Query $q) use ($matchday) {
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
                    $rating->points_no_bonus = $matchday->season->bonus_points ? $rating->calcNoBonusPoints() : $rating->points;
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
                        $this->io->err($value);
                        $this->io->err(print_r($value->getErrors()));
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

    public function calculateKey(Season $season, $encryptedFilePath = null, $dectyptedFilePath = null)
    {
        $this->io->out('Calculating decrypting key');
        if (is_null($encryptedFilePath)) {
            $encryptedFilePath = RATINGS_CSV . $season->year . DS . "mcc00.mxm";
        }
        if (is_null($dectyptedFilePath)) {
            $dectyptedFilePath = TMP . "0.txt";
        }
        if (!file_exists($encryptedFilePath)) {
            $encryptedFilePath = $this->getRatingsFile(0);
        }
        $reply = 'y';
        if (!file_exists($dectyptedFilePath)) {
            //if ($this->interactive) {
                $reply = $this->io->askChoice('Copy decrypted file in ' . $dectyptedFilePath . ' and then press enter. If you don\'t have one go to http://fantavoti.francesco-pompili.it/Decript.aspx', ['y', 'n'], 'y');
            //}
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
            $this->io->out('Key: ' . $res);
            $season->key_gazzetta = $res;
            if ($this->Seasons->save($season)) {
                copy($dectyptedFilePath, $dectyptedFilePath . "." . $season->year . ".bak");
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

    public function fixPoints(Season $season)
    {
        $this->io->out('Fix Points');
        $this->Seasons->loadInto($season, ['Matchdays.Ratings.Members.Roles']);
        foreach ($this->season->matchdays as $matchday) {
            $ratings = [];
            foreach ($matchday->ratings as $rating) {
                $rating->points_no_bonus = $season->bonus_points ? $rating->calcNoBonusPoints() : $rating->points;
                $ratings[] = $rating;
            }
            $this->Ratings->saveMany($ratings);
        }
    }
}
