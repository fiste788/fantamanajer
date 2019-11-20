<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\Championship;
use App\Model\Entity\Lineup;
use App\Model\Entity\Matchday;
use App\Model\Entity\Score;
use App\Model\Entity\Team;
use Cake\Mailer\Mailer;
use Cake\Utility\Hash;

/**
 * PointsMailer mailer.
 */
class WeeklyScriptMailer extends Mailer
{
    /**
     * Mailer's name.
     *
     * @var string
     */
    public static $name = 'WeeklyScript';

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Team $team Team
     * @param \App\Model\Entity\Matchday $matchday MAtchay
     * @param array $ranking Ranking
     * @param \App\Model\Entity\Lineup|null $details Details
     * @param \App\Model\Entity\Score $score Score
     * @return void
     */
    public function score(Team $team, Matchday $matchday, array $ranking, ?Lineup $details, Score $score)
    {
        $dispositions = null;
        $regulars = null;
        if ($details != null) {
            $dispositions = $details->dispositions;
            $regulars = array_splice($dispositions, 0, 11);
        }
        $this->setViewVars([
            'details' => $details,
            'ranking' => $ranking,
            'score' => $score,
            'regulars' => $regulars,
            'notRegulars' => $dispositions,
            'baseUrl' => 'https://fantamanajer.it',
        ])
            ->setSubject(sprintf('Punteggio %s giornata %d: %d', $team->name, $matchday->number, $score->points))
            ->setEmailFormat('html')
            ->setTo($team->user->email)
            ->send();
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Championship $championship Championship
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param \App\Model\Entity\Team[] $teams Teams
     * @return void
     */
    public function lineups(Championship $championship, Matchday $matchday, array $teams)
    {
        $addresses = Hash::extract($championship->teams, '{*}.user.email');
        $this->setViewVars(
            [
                'teams' => $teams,
                'baseUrl' => 'https://fantamanajer.it',
            ]
        )
            ->setSubject('Formazioni giornata ' . $matchday->number)
            ->setEmailFormat('html')
            ->setBcc($addresses)
            ->send();
    }
}
