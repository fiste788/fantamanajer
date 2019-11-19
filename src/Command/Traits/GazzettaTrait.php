<?php
declare(strict_types=1);

namespace App\Command\Traits;

use App\Model\Entity\Matchday;
use App\Model\Entity\Member;
use App\Model\Entity\Season;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Datasource\Exception\RecordNotFoundException;
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
 * @property \App\Model\Table\RatingsTable $Ratings
 */
trait GazzettaTrait
{
    /**
     *
     * @var \Cake\Console\ConsoleIo
     */
    private $io;

    /**
     *
     * @var \Cake\Console\Arguments
     */
    private $args;

    /**
     * Startup
     *
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io Io
     * @return void
     */
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

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param int $offsetGazzetta Offset
     * @param bool $forceDownload Force download
     * @return string|null
     */
    public function getRatings(Matchday $matchday, $offsetGazzetta = 0, $forceDownload = false): ?string
    {
        $year = $matchday->season->year;
        $folder = new Folder(RATINGS_CSV . $year, true);
        $pathCsv = $folder->path . DS . "Matchday" . str_pad((string)$matchday->number, 2, "0", STR_PAD_LEFT) . ".csv";
        $file = new File($pathCsv);
        $this->io->out("Search file in path " . $file->path);
        if ($file->exists() && $file->size() > 0 && !$forceDownload) {
            return $pathCsv;
        } else {
            $file = TMP . 'mcc' . str_pad((string)$matchday->number, 2, "0", STR_PAD_LEFT) . '.mxm';
            $this->io->verbose($file);
            if ($forceDownload || !file_exists($file)) {
                return $this->downloadRatings($matchday, $pathCsv, ($matchday->number + $offsetGazzetta));
            } else {
                return $this->downloadRatings($matchday, $pathCsv, ($matchday->number + $offsetGazzetta), $file);
            }
        }
    }

    /**
     * Download ratings
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param string $path Path
     * @param int $matchdayGazzetta Matchday gazzetta
     * @param string $url Url
     * @return string|null
     */
    private function downloadRatings(
        Matchday $matchday,
        string $path,
        int $matchdayGazzetta,
        ?string $url = null
    ): ?string {
        if (is_null($url)) {
            $url = $this->getRatingsFile($matchdayGazzetta);
        }
        if (!empty($url)) {
            $content = $this->decryptMXMFile($matchday, $url);
            if (!empty($content)) {
                $this->writeCsvRatings($content, $path);
                //self::writeXmlVoti($content, $percorsoXml);
                return $path;
            }
        }
    }

    /**
     * Write csv ratings
     *
     * @param string $content Content
     * @param string $path Path
     * @return void
     */
    public function writeCsvRatings(string $content, string $path): void
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
     * Undocumented function
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param string $path Path
     * @return string|null
     */
    public function decryptMXMFile(Matchday $matchday, ?string $path = null): ?string
    {
        $this->io->out("Starting decrypt " . $path);
        $decrypt = $matchday->season->key_gazzetta;
        if ($path) {
            $p_file = fopen($path, "r");
            if ($p_file) {
                $body = "";
                $explode_xor = explode("-", $decrypt);
                $i = 0;
                $content = file_get_contents($path);
                if (!empty($content)) {
                    while (!feof($p_file)) {
                        //$this->io->verbose($i);
                        if ($i == count($explode_xor)) {
                            $i = 0;
                        }

                        $line = fgets($p_file, 2);
                        if ($line) {
                            $xor2 = hexdec(bin2hex($line)) ^ hexdec($explode_xor[$i]);
                            $body .= chr($xor2);
                        } else {
                            $this->io->out("salto " . substr($body, -5));
                        }
                        $i++;
                    }
                }
                fclose($p_file);

                return $body;
            }
        }
    }

    /**
     * Get ratings file
     *
     * @param int $matchday Matchday
     * @return string|null
     */
    public function getRatingsFile(int $matchday): ?string
    {
        $this->io->out("Search ratings on maxigames");
        $http = new Client();
        $http->setConfig('headers', [
            'Connection' => 'keep-alive',
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip, deflate',
            'Cache-Control' => 'no-cache',
            'User-Agent' => 'PostmanRuntime/7.15.2',
        ]);
        $url = "https://maxigames.maxisoft.it/downloads.php";
        $this->io->verbose("Downloading " . $url);
        $response = $http->get($url);
        if ($response->isOk()) {
            $this->io->out("Maxigames found");
            $crawler = new Crawler();
            $crawler->addContent($response->getStringBody());
            $tr = $crawler->filter(".container .col-sm-9 tr:contains('Giornata $matchday')");
            if ($tr->count() > 0) {
                $this->io->out("Ratings found for matchday");
                $url = $this->getUrlFromMatchdayRow($tr);

                if ($url != null) {
                    return $this->downloadDropboxUrl($url, $matchday, $http);
                }
            }
        }

        return null;
    }

    /**
     * Get url From row
     *
     * @param \Symfony\Component\DomCrawler\Crawler $tr Row
     * @return string|null
     */
    private function getUrlFromMatchdayRow(Crawler $tr): ?string
    {
        $button = $tr->selectButton("DOWNLOAD");
        if (!$button->count()) {
            $link = $tr->selectLink("DOWNLOAD");
            if ($link->count()) {
                return $link->link()->getUri();
            }
        } else {
            $matches = sscanf($button->attr("onclick") ?? "", "window.open('%[^']");
            //preg_match("/window.open\(\'(.*?)\'#is/",,$matches);
            return $matches[0];
        }
    }

    /**
     * Download dropbox url
     *
     * @param string $url Url
     * @param int $matchday Matchday
     * @param \Cake\Http\Client $http Client
     * @return string|null
     */
    private function downloadDropboxUrl(string $url, int $matchday, Client $http): ?string
    {
        if ($url) {
            $this->io->verbose("Downloading " . $url);
            $response = $http->get($url);
            $this->io->verbose("Response " . $response->getStatusCode());
            if ($response->isOk()) {
                $crawler = new Crawler();
                $crawler->addContent($response->getStringBody());
                $button = $crawler->filter("#default_content_download_button");
                if ($button->count()) {
                    $url = $button->attr("href");
                } else {
                    $url = str_replace("www", "dl", $url);
                }
                $this->io->out("Downloading $url in tmp dir");
                $file = TMP . $matchday . '.mxm';
                file_put_contents($file, file_get_contents($url ?? ""));

                return $file;
            } else {
                $this->io->err('Response not ok');
            }
        }
    }

    /**
     * Update members
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param string $path Path
     * @return void
     */
    public function updateMembers(Matchday $matchday, ?string $path = null): void
    {
        $matchdayNumber = $matchday->number;
        $this->io->out('Updating members of matchday ' . $matchdayNumber);
        while ($path == null && $matchdayNumber > -1) {
            /** @var \App\Model\Entity\Matchday $matchday */
            $matchday = $this->Matchdays->find()->contain(['Seasons'])->where([
                'number' => $matchdayNumber,
                'season_id' => $matchday->season_id,
            ])->first();
            $path = $this->getRatings($matchday);
            $matchdayNumber--;
        }
        if ($path != null && file_exists($path)) {
            $query = $this->Members->find(
                'list',
                [
                    'keyField' => 'code_gazzetta',
                    'valueField' => function ($obj) {
                        return $obj;
                    },
                    'contain' => ['Players'],
                ]
            )->where(['season_id' => $matchday->season_id]);
            $oldMembers = $query->toArray();
            $newMembers = $this->returnArray($path, ";");
            //$this->abort(print_r($oldMembers,1));
            //$this->io->out($rolesById);
            $buys = [];
            $sells = [];
            $membersToSave = [];
            $member = null;
            foreach ($newMembers as $id => $newMember) {
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
                    $sells[$oldMember->club_id][] = $oldMember;
                }
            }
            //$this->io->verbose($membersToSave);
            $this->io->out("Savings " . count($membersToSave) . " members");
            if (!$this->Members->saveMany($membersToSave)) {
                $ev = new \Cake\Event\Event('Fantamanajer.memberTransferts', $this, [
                    'sells' => $sells,
                    'buys' => $buys,
                ]);
                \Cake\Event\EventManager::instance()->dispatch($ev);
                foreach ($membersToSave as $value) {
                    if (!empty($value->getErrors())) {
                        $this->io->err($value);
                        $this->io->err(print_r($value->getErrors(), true));
                    }
                }
            }
        } else {
            $this->abort(0);
        }
    }

    /**
     * Member transfert
     *
     * @param \App\Model\Entity\Member $member Member
     * @param string $club Club
     * @return \App\Model\Entity\Member|null
     */
    private function memberTransfert(Member $member, $club): ?Member
    {
        $flag = false;
        if (!$member->active) {
            $member->active = true;
            $flag = true;
        }

        /** @var \App\Model\Entity\Club $clubNew */
        $clubNew = $this->Clubs->find()->where(['name' => ucwords(strtolower(trim($club, '"')))])->first();
        if ($member->club_id != $clubNew->id) {
            $this->io->verbose("Transfert member " . $member->player->full_name);
            $member->club = $clubNew;
            $member->active = true;
            $flag = true;
        }
        if ($flag) {
            return $member;
        }
    }

    /**
     * Member new
     *
     * @param array $member Member
     * @param \App\Model\Entity\Season $season Season
     * @return \App\Model\Entity\Member
     */
    private function memberNew(array $member, Season $season): Member
    {
        $esprex = "/[A-Z']*\s?[A-Z']{2,}/";
        $fullname = trim($member[2], '"');
        $ass = null;
        preg_match($esprex, $fullname, $ass);
        $surname = ucwords(strtolower((!empty($ass) ? $ass[0] : $fullname)));
        $name = ucwords(strtolower(trim(substr($fullname, strlen($surname)))));
        //$queryPlayer = $this->Players->find()->where();
        $player = $this->Players->findOrCreate(
            [
                'surname' => $surname,
                'name' => $name,
            ],
            null,
            ['atomic' => false]
        );
        //$queryClub = $this->Clubs->findByName();
        $club = $this->Clubs->findOrCreate(
            ['name' => ucwords(strtolower(trim($member[3], '"')))],
            null,
            ['atomic' => false]
        );
        $this->io->verbose("Add new member " . $surname . " " . $name);

        return $this->Members->newEntity(
            [
                'season_id' => $season->id,
                'code_gazzetta' => $member[0],
                'playmaker' => $member[26],
                'active' => true,
                'role_id' => $member[5] + 1,
                'club_id' => $club->id,
                'player_id' => $player->id,
            ]
        );
    }

    /**
     * Import ratings
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param string|null $path Path
     * @return bool
     */
    public function importRatings(Matchday $matchday, ?string $path = null): bool
    {
        $path = $path ? $path : $this->getRatings($matchday);
        if ($path) {
            $csvRow = $this->returnArray($path, ";");
            $members = $this->Members->findListBySeasonId($matchday->season_id)
                ->contain(['Roles', 'Ratings' => function (Query $q) use ($matchday) {
                    return $q->where(['matchday_id' => $matchday->id]);
                }])->toArray();

            $ratings = [];
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
                            'matchday_id' => $matchday->id,
                        ]
                    );
                    $rating->points_no_bonus = $matchday->season->bonus_points ?
                        $rating->calcNoBonusPoints() : $rating->points;
                    $ratings[] = $rating;
                } else {
                    throw new RecordNotFoundException("No member for code_gazzetta $stats[0]");
                }
            }

            if (
                !$this->Ratings->saveMany($ratings, [
                    'checkExisting' => false,
                    'associated' => false,
                    'checkRules' => false,
                ])
            ) {
                foreach ($ratings as $value) {
                    if (!empty($value->getErrors())) {
                        $this->io->err($value);
                        $this->io->err(print_r($value->getErrors(), true));
                    }
                }

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Return array
     *
     * @param string $path Path
     * @param string $sep Sep
     * @param bool $header Header
     * @return array
     */
    public function returnArray(string $path, string $sep = ";", bool $header = false): array
    {
        $arrayOk = [];
        $content = file_get_contents($path ?? "");
        if ($content != false) {
            $array = explode("\n", trim($content));
            if ($header) {
                array_shift($array);
            }

            foreach ($array as $val) {
                $par = explode($sep, $val);
                $array = trim($val);
                $arrayOk[$par[0]] = $par;
            }
        }

        return $arrayOk;
    }

    /**
     * Calculate key
     *
     * @param \App\Model\Entity\Season $season Season
     * @param string|null $encryptedFilePath Encrypted file path
     * @param string|null $dectyptedFilePath Decrypted file path
     * @return string|null
     */
    public function calculateKey(
        Season $season,
        ?string $encryptedFilePath = null,
        ?string $dectyptedFilePath = null
    ): ?string {
        $this->io->out('Calculating decrypting key');
        if (is_null($encryptedFilePath)) {
            $encryptedFilePath = RATINGS_CSV . $season->year . DS . "mcc00.mxm";
        }
        if (is_null($dectyptedFilePath)) {
            $dectyptedFilePath = TMP . "0.txt";
        }
        if (!file_exists($encryptedFilePath)) {
            $encryptedFilePath = $this->getRatingsFile(0) ?? "";
        }
        $reply = 'y';
        if (!file_exists($dectyptedFilePath)) {
            $reply = $this->io->askChoice(
                'Copy decrypted file in ' . $dectyptedFilePath . ' and then press enter. 
                If you don\'t have one go to http://fantavoti.francesco-pompili.it/Decript.aspx',
                ['y', 'n'],
                'y'
            );
        }
        if ($reply == 'y') {
            $decript = file_get_contents($dectyptedFilePath);
            $encript = file_get_contents($encryptedFilePath);
            if ($decript != false && $encript != false) {
                $res = [];
                for ($i = 0; $i < 28; $i++) {
                    $xor1 = hexdec(bin2hex($decript[$i]));
                    $xor2 = hexdec(bin2hex($encript[$i]));
                    $res[] = dechex($xor1 ^ $xor2);
                }
                $key = implode("-", $res);
                $this->io->out('Key: ' . $key);
                $season->key_gazzetta = $key;
                if ($this->Seasons->save($season)) {
                    copy($dectyptedFilePath, $dectyptedFilePath . "." . $season->year . ".bak");
                    unlink($dectyptedFilePath);

                    return $key;
                }
            }
        }
    }

    /**
     * Fix porints
     *
     * @param \App\Model\Entity\Season $season Season
     * @return void
     */
    public function fixPoints(Season $season): void
    {
        $this->io->out('Fix Points');
        $this->Seasons->loadInto($season, ['Matchdays.Ratings.Members.Roles']);
        foreach ($season->matchdays as $matchday) {
            $ratings = [];
            foreach ($matchday->ratings as $rating) {
                $rating->points_no_bonus = $rating->calcPointsNoBonus($season);
                if ($rating->isDirty()) {
                    $ratings[] = $rating;
                }
            }
            $this->Ratings->saveMany($ratings);
        }
    }
}
