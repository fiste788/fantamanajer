<?php

namespace App\Model\Table;

use App\Model\Entity\Lineup;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Lineups Model
 *
 * @property MembersTable|BelongsTo $Members
 * @property MembersTable|BelongsTo $Members
 * @property MembersTable|BelongsTo $Members
 * @property MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property DispositionsTable|\Cake\ORM\Association\HasMany $Dispositions
 * @property ScoresTable|\Cake\ORM\Association\HasOne $Scores
 * @property \App\Model\Table\View0LineupsDetailsTable|HasMany $View0LineupsDetails
 *
 * @method \App\Model\Entity\Lineup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Lineup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Lineup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Lineup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Lineup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Lineup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Lineup findOrCreate($search, callable $callback = null, $options = [])
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $Captain
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $VCaptain
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $VVCaptain
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Lineup|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class LineupsTable extends Table
{

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('lineups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior(
            'Timestamp',
            [
                'events' => [
                    'Model.beforeSave' => [
                        'created_at' => 'new',
                        'modified_at' => 'always'
                    ]
                ]
            ]
        );

        $this->belongsTo(
            'Captain',
            [
                'className' => 'Members',
                'foreignKey' => 'captain_id'
            ]
        );
        $this->belongsTo(
            'VCaptain',
            [
                'className' => 'Members',
                'foreignKey' => 'vcaptain_id'
            ]
        );
        $this->belongsTo(
            'VVCaptain',
            [
                'className' => 'Members',
                'foreignKey' => 'vvcaptain_id'
            ]
        );
        $this->belongsTo(
            'Matchdays',
            [
                'foreignKey' => 'matchday_id',
                'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Teams',
            [
                'foreignKey' => 'team_id',
                'joinType' => 'INNER'
            ]
        );
        $this->hasMany(
            'Dispositions',
            [
                'foreignKey' => 'lineup_id',
                'sort' => ['Dispositions.position'],
                'saveStrategy' => 'replace'
            ]
        );
        $this->hasOne(
            'Scores',
            [
                'foreignKey' => 'lineup_id'
            ]
        );
        $this->hasMany(
            'View0LineupsDetails',
            [
                'foreignKey' => 'lineup_id'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('module', 'create')
            ->notEmpty('module');

        $validator
            ->boolean('jolly')
            ->allowEmpty('jolly');

        $validator
            ->boolean('cloned')
            ->allowEmpty('cloned');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['captain_id'], 'Captain'));
        $rules->add($rules->existsIn(['vcaptain_id'], 'VCaptain'));
        $rules->add($rules->existsIn(['vvcaptain_id'], 'VVCaptain'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->isUnique(['team_id','matchday_id'], __('Lineup already exists for this matchday. Try to refresh')));
        $rules->addCreate(
            function (Lineup $entity, $options) {
                $matchday = $this->Matchdays->get($entity->matchday_id);
                $team = $this->Teams->get($entity->team_id, ['contain' => ['Championships']]);
                return $matchday->date->subMinutes($team->championship->minute_lineup)->isFuture();
            },
            'Expired',
            ['errorField' => 'module', 'message' => __('Expired lineup')]
        );
        $rules->add(
            function (Lineup $entity, $options) {
                for($i = 0; $i < 11; $i++) {
                    if(!array_key_exists($i, $entity->dispositions)) {
                        return false;
                    } else if($entity->dispositions[$i]->position != ($i + 1)) {
                        return false;
                    }
                }
                return true;
            },
            'MissingPlayer',
            ['errorField' => 'module', 'message' => __('Missing player/s')]
        );
        
        $rules->add(
            function (Lineup $entity, $options) {
                if ($entity->jolly) {
                    $matchday = $this->Matchdays->get($entity->matchday_id);
                    $matchdays = $this->Matchdays->find()
                        ->where(['season_id' => $matchday->season_id])
                        ->count();

                    return $this->find()
                        ->contain(['Matchdays'])
                        ->innerJoinWith('Matchdays')
                        ->where([
                            'Lineups.id IS NOT' => $entity->id,
                            'jolly' => true,
                            'team_id' => $entity->team_id,
                            'Matchdays.number ' . ($matchday->number <= $matchdays / 2 ? '<=' : '>') => $matchdays / 2
                        ])
                        ->isEmpty();
                }

                return true;
            },
            'JollyAlreadyUsed',
            ['errorField' => 'jolly', 'message' => __('Jolly already used')]
        );

        return $rules;
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $event = new Event('Fantamanajer.newLineup', $this, [
                'lineup' => $entity
            ]);
            EventManager::instance()->dispatch($event);
        }
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (array_key_exists('created_at', $data)) {
            unset($data['created_at']);
        }
        if (array_key_exists('modified_at', $data)) {
            unset($data['modified_at']);
        }
    }

    public function findDetails(Query $q, array $options)
    {
        return $q->contain([
            'Teams',
            'Dispositions' => [
                'Members' => [
                    'Roles', 'Players', 'Clubs', 'Ratings' => function ($q) use ($options) {
                        return $q->where(['matchday_id' => $options['matchday_id']]);
                    }]
                ]
            ])->where([
                'team_id' => $options['team_id'],
                'matchday_id' => $options['matchday_id']
            ]);
    }

    /**
     *
     * @param Query $q
     * @param array $options
     * @return Query
     */
    public function findLast(Query $q, array $options)
    {
        return $q->innerJoinWith('Matchdays')
                ->contain(['Dispositions'])
                ->where([
                    'Lineups.team_id' => $options['team_id'],
                    'Lineups.matchday_id <=' => $options['matchday']->id,
                    'Matchdays.season_id' => $options['matchday']->season_id
                ])
                ->order(['Matchdays.number' => 'DESC']);
    }

    public function findByMatchdayIdAndTeamId(Query $q, array $options)
    {
        return $q->contain(['Dispositions'])
                ->where([
                    'Lineups.team_id' => $options['team_id'],
                    'Lineups.matchday_id =' => $options['matchday_id'],
                ]);
    }

    public function findWithRatings(Query $q, array $options)
    {
        $matchdayId = $options['matchday_id'];

        return $q->contain([
                'Teams.Championships',
                'Dispositions' => [
                    'Members' => function (Query $q) use ($matchdayId) {
                        return $q->find(
                            'list',
                            [
                                        'keyField' => 'id',
                                        'valueField' => function ($obj) {
                                            return $obj;
                                        }
                                    ]
                        )
                                ->contain(
                                    ['Ratings' => function (Query $q) use ($matchdayId) {
                                        return $q->where(['Ratings.matchday_id' => $matchdayId]);
                                    }
                                    ]
                                );
                    }
                ]]);
    }
    
    public function createLineup($team_id, $matchday_id) {
        $lineup = $this->newEntity();
        $lineup->modules = Lineup::$module;
        $lineup->team_id = $team_id;
        $lineup->matchday_id = $matchday_id;
        $lineup->team = $this->Teams->get($team_id, [
            'contain' => [
                'Members' => [
                    'Roles', 'Players'
                ]
            ]
        ]);
        return $lineup;
    }
    
    /**
     * 
     * @param \App\Model\Entity\Member[] $members
     */
    public function getLikelyLineup($members) {
        $client = new Client([
            'base_uri' => 'https://www.gazzetta.it'
        ]);
        $html = $client->request('GET', '/Calcio/prob_form', ['verify' => false]);
        if($html->getStatusCode() == 200) {
            $crawler = new Crawler($html->getBody()->getContents());
            $matches = $crawler->filter('.matchFieldContainer');
            $teams = [];
            $matches->each(function (Crawler $match) use(&$teams) {
                $i = 0;
                $teamsName = $match->filter('.match .team')->extract(['_text']);
                $regulars = $match->filter('.team-players-inner');
                $details = $match->filter('.matchDetails > div');
                foreach($teamsName as $team) {
                    $teams[trim($team)]['regulars'] = $regulars->eq($i);
                    $teams[trim($team)]['details'] = $details->eq($i);
                    $i++;
                }
            });
            foreach($members as &$member) {
                $divs = $teams[strtolower($member->club->name)];
                $member->likely_lineup = new \stdClass();
                $member->likely_lineup->regular = null;
                $find = $divs['regulars']->filter('li:contains("' . strtoupper($member->player->surname) . '")');
                if($find->count() > 0) {
                    $member->likely_lineup->regular = true;
                } else {
                    $find = $divs['details']->filter('p:contains("' . strtoupper($member->player->surname) . '")');
                    if($find->count() == 0) {
                        $find = $divs['details']->filter('p:contains("' . $member->player->surname . '")');
                    }
                    if($find->count() > 0) {
                        $title = $find->filter("strong")->text();
                        switch($title) {
                            case "Panchina:": $member->likely_lineup->regular = false;break;
                            case "Squalificati:": $member->likely_lineup->disqualified = true;break;
                            case "Indisponibili:": $member->likely_lineup->injured = true;break;
                            case "Ballottaggio:": $member->likely_lineup->second_ballot = 50;break;
                        }
                    }
                }
            }
        }
    }
}
