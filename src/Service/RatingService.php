<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Console\ConsoleIo;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ModelAwareTrait;
use Cake\ORM\Query;

/**
 * Undocumented class
 *
 * @property \App\Service\DownloadRatingsService $DownloadRatings
 * @property \App\Model\Table\RatingsTable $Ratings
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MembersTable $Members
 */
class RatingService
{
    use ServiceAwareTrait;
    use ModelAwareTrait;

    /**
     * @var \Cake\Console\ConsoleIo|null
     */
    private $io;

    /**
     * Undocumented function
     *
     * @param \Cake\Console\ConsoleIo $io IO
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     */
    public function __construct(ConsoleIo $io)
    {
        $this->io = $io;
        $this->loadService('DownloadRatings', [$io]);
        $this->loadModel('Ratings');
        $this->loadModel('Seasons');
        $this->loadModel('Members');
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
                    $xor1 = (int)hexdec(bin2hex($decript[$i]));
                    $xor2 = (int)hexdec(bin2hex($encript[$i]));
                    $res[] = dechex($xor1 ^ $xor2);
                }
                $key = implode('-', $res);
                if ($this->io != null) {
                    $this->io->out('Key: ' . $key);
                }
                $season->key_gazzetta = $key;
                if ($this->Seasons->save($season)) {
                    copy($dectyptedFilePath, $dectyptedFilePath . '.' . $season->year . '.bak');
                    unlink($dectyptedFilePath);

                    return $key;
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
     */
    public function importRatings(Matchday $matchday, ?string $path = null): bool
    {
        $path = $path ? $path : $this->DownloadRatings->getRatings($matchday);
        if ($path) {
            $csvRow = $this->DownloadRatings->returnArray($path, ';');

            /** @var \App\Model\Entity\Member[] $members */
            $members = $this->Members->findListBySeasonId($matchday->season_id)
                ->contain(['Roles', 'Ratings' => function (Query $q) use ($matchday): Query {
                    return $q->where(['matchday_id' => $matchday->id]);
                }])->toArray();

            $ratings = [];
            foreach ($csvRow as $stats) {
                if (array_key_exists($stats[0], $members)) {
                    $member = $members[$stats[0]];
                    $rating = empty($member->ratings) ? $this->Ratings->newEmptyEntity() : $member->ratings[0];
                    $rating->member_id = $member->id;
                    $rating = $this->Ratings->patchEntity($rating, [
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
                !$this->Ratings->saveMany($ratings, [
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
