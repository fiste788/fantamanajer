<?php
declare(strict_types=1);

namespace App\Service;

use AllowDynamicProperties;
use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Console\ConsoleIo;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query\SelectQuery;

/**
 * @property \App\Service\DownloadRatingsService $DownloadRatings
 */
#[AllowDynamicProperties]
class RatingService
{
    use LocatorAwareTrait;
    use ServiceAwareTrait;

    /**
     * @var \Cake\Console\ConsoleIo|null
     */
    private ?ConsoleIo $io = null;

    /**
     * Undocumented function
     *
     * @param \Cake\Console\ConsoleIo $io IO
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function __construct(ConsoleIo $io)
    {
        $this->io = $io;
        $this->loadService('DownloadRatings', [$io]);
    }

    /**
     * Calculate key
     *
     * @param \App\Model\Entity\Season $season Season
     * @param string|null $encryptedFilePath Encrypted file path
     * @param string|null $dectyptedFilePath Decrypted file path
     * @return string|null
     * @throws \Cake\Core\Exception\CakeException
     */
    public function calculateKey(
        Season $season,
        ?string $encryptedFilePath = null,
        ?string $dectyptedFilePath = null
    ): ?string {
        if ($this->io != null) {
            $this->io->out('Calculating decrypting key');
        }
        if (is_null($encryptedFilePath)) {
            $encryptedFilePath = RATINGS_CSV . $season->year . DS . 'mcc00.mxm';
        }
        if (is_null($dectyptedFilePath)) {
            $dectyptedFilePath = TMP . '0.txt';
        }
        if (!file_exists($encryptedFilePath)) {
            $encryptedFilePath = $this->DownloadRatings->getRatingsFile(0) ?? '';
        }
        $reply = 'y';
        if (!file_exists($dectyptedFilePath) && $this->io != null) {
            $reply = $this->io->askChoice(
                'Copy decrypted file in ' . $dectyptedFilePath . ' and then press enter.
                If you don\'t have one go to https://www.fr3nsis.com/',
                ['y', 'n'],
                'y'
            );
        }
        if ($reply == 'y') {
            $decript = file_get_contents($dectyptedFilePath);
            $encript = file_get_contents($encryptedFilePath);
            if ($decript != false && $encript != false) {
                $keyCount = 28;
                $keys = [];
                for ($j = 0; $j < 6; $j++) {
                    $res = [];
                    for ($i = $j * $keyCount; $i < ($j + 1) * $keyCount; $i++) {
                        $xor1 = hexdec(bin2hex($decript[$i]));
                        $xor2 = hexdec(bin2hex($encript[$i]));
                        $res[] = dechex($xor1 ^ $xor2);
                    }
                    $tmp = implode('-', $res);
                    if ($this->io != null) {
                        $this->io->info($tmp);
                    }
                    $keys[] = $tmp;
                }
                $keys = array_unique($keys);
                if (count($keys) == 1) {
                    $key = $keys[0];
                    if ($this->io != null) {
                        $this->io->out('Key: ' . $key);
                    }
                    $season->key_gazzetta = $key;
                    $seasonsTable = $this->fetchTable('Seasons');
                    if ($seasonsTable->save($season)) {
                        copy($dectyptedFilePath, $dectyptedFilePath . '.' . $season->year . '.bak');
                        unlink($dectyptedFilePath);

                        return $key;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Import ratings
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param string|null $path Path
     * @return bool
     * @throws \Cake\Core\Exception\CakeException
     */
    public function importRatings(Matchday $matchday, ?string $path = null): bool
    {
        $path = $path ? $path : $this->DownloadRatings->getRatings($matchday);
        if ($path) {
            $csvRow = $this->DownloadRatings->returnArray($path, ';');

            /** @var \App\Model\Table\MembersTable $membersTable */
            $membersTable = $this->fetchTable('Members');
            /** @var array<\App\Model\Entity\Member> $members */
            $members = $membersTable->findListBySeasonId($matchday->season_id)
                ->contain([
                    'Roles',
                    'Ratings' => function (SelectQuery $q) use ($matchday): SelectQuery {
                        return $q->where(['matchday_id' => $matchday->id]);
                    },
                ])->toArray();

            /** @var \App\Model\Table\RatingsTable $ratingsTable */
            $ratingsTable = $this->fetchTable('Ratings');
            $ratings = [];
            foreach ($csvRow as $stats) {
                if (array_key_exists($stats[0], $members)) {
                    $member = $members[$stats[0]];
                    $rating = empty($member->ratings) ? $ratingsTable->newEmptyEntity() : $member->ratings[0];
                    $rating->member_id = $member->id;
                    $rating = $ratingsTable->patchEntity($rating, [
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
                    ], ['accessibleFields' => ['*' => true]]);
                    $ratings[] = $rating;
                } else {
                    throw new RecordNotFoundException("No member for code_gazzetta $stats[0]");
                }
            }

            if (
                !$ratingsTable->saveMany($ratings, [
                    'checkExisting' => false,
                    'associated' => false,
                    'checkRules' => false,
                ])
            ) {
                foreach ($ratings as $value) {
                    if (!empty($value->getErrors()) && $this->io != null) {
                        $this->io->err(print_r($value, true));
                        $this->io->err(print_r($value->getErrors(), true));
                    }
                }

                return false;
            }

            return true;
        }

        return false;
    }
}
