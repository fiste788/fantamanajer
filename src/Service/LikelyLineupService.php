<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Member;
use App\Model\Entity\Team;
use Cake\Collection\Collection;
use Cake\ORM\Locator\LocatorAwareTrait;
use GuzzleHttp\Client;
use InvalidArgumentException;
use RuntimeException;
use LogicException;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;

class LikelyLineupService
{
    use LocatorAwareTrait;

    /**
     * Team array
     *
     * @var array<string, array<\Symfony\Component\DomCrawler\Crawler>>
     */
    private array $_teams = [];

    /**
     * Team array
     *
     * @var array<string, string>
     */
    private array $_versus = [];

    /**
     * Entry function
     *
     * @param int $teamId The id of team
     * @return \App\Model\Entity\Team
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function get(int $teamId): Team
    {
        /** @var \App\Model\Table\TeamsTable $teamsTable */
        $teamsTable = $this->fetchTable('Teams');
        $team = $teamsTable->get($teamId, [
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
     *
     * @param array<\App\Model\Entity\Member> $members The members
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function retrieve(array $members): void
    {
        $client = new Client([
            'base_uri' => 'https://www.gazzetta.it',
        ]);
        $html = $client->request('GET', '/Calcio/prob_form');
        if ($html->getStatusCode() == 200) {
            $crawler = new Crawler($html->getBody()->getContents());
            $matches = $crawler->filter('.matchFieldContainer');
            $matches->each(function (Crawler $match): void {
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
     * @throws \RuntimeException
     * @throws \LogicException
     */
    private function processMatch(Crawler $match): void
    {
        $i = 0;
        /** @var array<string> $teamsName */
        $teamsName = array_map(function (string $v) {
            $v = trim($v);
            $v = strtolower($v);

            return $v;
        }, $match->filter('.match .team')->extract(['_text']));
        $regulars = $match->filter('.team-players-inner');
        $details = $match->filter('.matchDetails > div');
        foreach ($teamsName as $team) {
            $this->_teams[$team]['regulars'] = $regulars->eq($i);
            $this->_teams[$team]['details'] = $details->eq($i);
            $this->_versus[$team] = array_values(array_diff($teamsName, [$team]))[0];
            $i++;
        }
    }

    /**
     * Process member
     *
     * @param \App\Model\Entity\Member $member The member
     * @return void
     * @throws \InvalidArgumentException
     */
    private function processMember(Member &$member): void
    {
        $club = strtolower($member->club->name);
        if (array_key_exists($club, $this->_teams)) {
            $divs = $this->_teams[$club];
            $member->likely_lineup = new stdClass();
            $member->likely_lineup->versus = $this->_versus[$club];
            $member->likely_lineup->regular = null;
            try {
                $find = $divs['regulars']->filter('li:contains("' . strtoupper($member->player->surname) . '")');
                if ($find->count() > 0) {
                    $member->likely_lineup->regular = true;
                }
                $find = $divs['details']->filter('p:contains("' . strtoupper($member->player->surname) . '")');
                if ($find->count() == 0) {
                    $find = $divs['details']->filter('p:contains("' . $member->player->surname . '")');
                }
            } catch (RuntimeException | LogicException $e) {
                $find = null;
            }
            if ($find != null && $find->count() > 0) {
                try {
                    $title = $find->filter('strong')->text();
                } catch (InvalidArgumentException | RuntimeException | LogicException $e) {
                    $title = '';
                }
                switch ($title) {
                    case 'Panchina:':
                        $member->likely_lineup->regular = false;
                        break;
                    case 'Squalificati:':
                        $member->likely_lineup->disqualified = true;
                        break;
                    case 'Indisponibili:':
                        $member->likely_lineup->injured = true;
                        break;
                    case 'Ballottaggio:':
                        $ballots = new Collection(explode(',', str_replace($title, '', $find->text())));
                        $member->likely_lineup->second_ballot = $ballots->filter(function (string $bal) use ($member) {
                            return str_contains($bal, $member->player->surname);
                        })
                            ->map(function (string $ballot) use ($member) {
                                $pieces = explode(' ', trim($ballot));
                                $players = explode('-', $pieces[0]);
                                $perc = explode('-', $pieces[1]);
                                foreach ($players as $key => $players) {
                                    if (str_contains($players, $member->player->surname)) {
                                        return floatval(trim($perc[$key]));
                                    }
                                }
                            })->first();
                        break;
                }
            }
        }
    }
}
