<?php

namespace App\Service;

use App\Model\Entity\Member;
use Cake\ORM\TableRegistry;
use GuzzleHttp\Client;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;

class LikelyLineupService
{

    private $_teams = [];
    
    public function get($teamId) {
        $team = TableRegistry::getTableLocator()->get('Teams')->get($teamId, [
            'contain' => [
                'Members' => [
                    'Players',
                    'Clubs'
                ]
            ]
        ]);
        $this->retrieve($team->members);
        return $team;
    }

    /**
     * 
     * @param Member[] $members
     */
    public function retrieve($members)
    {   
        $client = new Client([
            'base_uri' => 'https://www.gazzetta.it'
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

    private function processMatch(Crawler $match)
    {
        $i = 0;
        $teamsName = $match->filter('.match .team')->extract(['_text']);
        $regulars = $match->filter('.team-players-inner');
        $details = $match->filter('.matchDetails > div');
        foreach ($teamsName as $team) {
            $this->_teams[trim($team)]['regulars'] = $regulars->eq($i);
            $this->_teams[trim($team)]['details'] = $details->eq($i);
            $i++;
        }
    }

    private function processMember(Member &$member)
    {
        $divs = $this->_teams[strtolower($member->club->name)];
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
                    case "Panchina:": $member->likely_lineup->regular = false;
                        break;
                    case "Squalificati:": $member->likely_lineup->disqualified = true;
                        break;
                    case "Indisponibili:": $member->likely_lineup->injured = true;
                        break;
                    case "Ballottaggio:": $member->likely_lineup->second_ballot = 50;
                        break;
                }
            }
        }
    }
}
