<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Member;
use App\Model\Entity\Team;
use Cake\Datasource\ModelAwareTrait;
use Cake\Log\Log;
use GuzzleHttp\Client;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;

/**
 *
 * @property \App\Model\Table\TeamsTable $Teams
 */
class LikelyLineupService
{
    use ModelAwareTrait;

    private $_teams = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadModel('Teams');
    }

    /**
     * Entry function
     *
     * @param int $teamId The id of team
     * @return \App\Model\Entity\Team
     */
    public function get(int $teamId): Team
    {
        $team = $this->Teams->get($teamId, [
            'contain' => [
                'Members' => [
                    'Players',
                    'Clubs',
                ],
            ],
        ]);
        $this->retrieve($team->members);

        return $team;
    }

    /**
     * Retrieve from gazzetta likely lineup
     * @param \App\Model\Entity\Member[] $members The members
     * @return void
     */
    public function retrieve(array $members): void
    {
        $client = new Client([
            'base_uri' => 'https://www.gazzetta.it',
        ]);
        $html = $client->request('GET', '/Calcio/prob_form', ['verify' => false]);
        if ($html->getStatusCode() == 200) {
            $crawler = new Crawler($html->getBody()->getContents());
            $matches = $crawler->filter('.matchFieldContainer');
            $matches->each(function (Crawler $match) {
                $this->processMatch($match);
            });
            foreach ($members as &$member) {
                $this->processMember($member);
            }
        }
    }

    /**
     * Process match
     *
     * @param \Symfony\Component\DomCrawler\Crawler $match The match
     * @return void
     */
    private function processMatch(Crawler $match): void
    {
        $i = 0;
        $teamsName = $match->filter('.match .team')->extract(['_text']);
        $regulars = $match->filter('.team-players-inner');
        $details = $match->filter('.matchDetails > div');
        foreach ($teamsName as $team) {
            $this->_teams[strtolower(trim($team))]['regulars'] = $regulars->eq($i);
            $this->_teams[strtolower(trim($team))]['details'] = $details->eq($i);
            $i++;
        }
    }

    /**
     * Process member
     *
     * @param \App\Model\Entity\Member $member The member
     * @return void
     */
    private function processMember(Member &$member): void
    {
        $divs = $this->_teams[strtolower($member->club->name)];
        if ($divs) {
            $member->likely_lineup = new stdClass();
            $member->likely_lineup->regular = null;
            $find = $divs['regulars']->filter('li:contains("' . strtoupper($member->player->surname) . '")');
            if ($find->count() > 0) {
                $member->likely_lineup->regular = true;
            } else {
                $find = $divs['details']->filter('p:contains("' . strtoupper($member->player->surname) . '")');
                if ($find->count() == 0) {
                    $find = $divs['details']->filter('p:contains("' . $member->player->surname . '")');
                }
                if ($find->count() > 0) {
                    $title = $find->filter("strong")->text();
                    switch ($title) {
                        case "Panchina:":
                            $member->likely_lineup->regular = false;
                            break;
                        case "Squalificati:":
                            $member->likely_lineup->disqualified = true;
                            break;
                        case "Indisponibili:":
                            $member->likely_lineup->injured = true;
                            break;
                        case "Ballottaggio:":
                            $member->likely_lineup->second_ballot = 50;
                            break;
                    }
                }
            }
        } else {
            Log::error("Non trovo team " . strtolower($member->club->name));
        }
    }
}
