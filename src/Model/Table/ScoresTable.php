<?php

namespace App\Model\Table;

use App\Model\Entity\Matchday;
use App\Model\Entity\Score;
use App\Model\Entity\Season;
use App\Model\Entity\Team;
use Cake\Collection\CollectionInterface;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Scores Model
 *
 * @property TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property \Cake\ORM\Association\HasOne $Lineup
 * @property LineupsTable|\Cake\ORM\Association\BelongsTo $Lineups
 * @method \App\Model\Entity\Score get($primaryKey, $options = [])
 * @method \App\Model\Entity\Score newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Score[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Score|bool save(EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Score patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Score[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Score findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Score|bool saveOrFail(EntityInterface $entity, $options = [])
 */
class ScoresTable extends Table
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

        $this->setTable('scores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Lineups',
            [
                'foreignKey' => 'lineup_id'
            ]
        );
        $this->belongsTo(
            'Teams',
            [
                'foreignKey' => 'team_id',
                'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Matchdays',
            [
                'foreignKey' => 'matchday_id',
                'joinType' => 'INNER'
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
            ->numeric('points')
            ->requirePresence('points', 'create')
            ->notEmpty('points');

        $validator
            ->numeric('real_points')
            ->requirePresence('real_points', 'create')
            ->notEmpty('real_points');

        $validator
            ->numeric('penality_points')
            ->requirePresence('penality_points', 'create')
            ->notEmpty('penality_points');

        $validator
            ->allowEmpty('penality');

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
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));

        return $rules;
    }

    /**
     *
     * @param Season $season
     * @return int
     */
    public function findMaxMatchday(Season $season)
    {
        $query = $this->find();
        $res = $query->hydrate(false)
            ->join(
                [
                    'table' => 'matchdays',
                    'alias' => 'm',
                    'type' => 'LEFT',
                    'conditions' => 'm.id = Scores.matchday_id',
                ]
            )
            ->select(['matchday_id' => $query->func()->max('Scores.matchday_id'), ])
            ->where(['m.season_id' => $season->id])
            ->first();

        return $res['matchday_id'];
    }

    public function findScores(Query $q, array $options)
    {
        /*if(!$rankingQuery) {
            $rankingQuery = $this->find('ranking', ['championship_id' => $championshipId]);
        }
        $ranking = $rankingQuery->toArray();
        $ids = Hash::extract($ranking,'{*}.team_id');*/
        $championshipId = $options['championship_id'];
        return $q->select(['id', 'points', 'team_id'])
            ->contain([
                'Matchdays' => ['fields' => ['number']],
            ])->innerJoinWith('Teams', function ($q) use ($championshipId) {
                return $q->where(['Teams.championship_id' => $championshipId]);
            })->formatResults(function (CollectionInterface $results) {
                return $results->combine('matchday.number', function ($entity) { 
                    unset($entity->matchday);
                    return $entity;
                },'team_id');
            }, true);
    }

    /**
     *
     * @param int $championshipId
     * @return mixed
     */
    public function findRanking(Query $q, array $options)
    {
        $championshipId = $options['championship_id'];
        $q->select([
                'team_id',
                'sum_points' => $q->func()->sum('points')
            ])->contain(['Teams' => ['fields' => ['id', 'name']]])
            ->innerJoinWith('Teams', function ($q) use ($championshipId) {
                return $q->where(['Teams.championship_id' => $championshipId]);
            })->group('team_id')
            ->orderDesc('sum_points');
            
        if(array_key_exists('scores', $options) && $options['scores']) {
            $q->formatResults(function (CollectionInterface $results) use ($championshipId) {
                $scores = $this->find('scores', [
                    'championship_id' => $championshipId
                ])->all()->toArray();
                return $results->map(function($entity) use ($scores) {
                    $entity['scores'] = $scores[$entity->team_id];
                    return $entity;
                });
            }, true);
        }
        return $q;
    }

    /**
     *
     * @param Team     $team
     * @param Matchday $matchday
     * @return Score
     * @throws PDOException
     */
    public function compute(Team $team, Matchday $matchday)
    {
        $score = $this->find()
            ->where(['team_id' => $team->id, 'matchday_id' => $matchday->id])
            ->first();
        if (!$score) {
            $score = $this->newEntity([
                'penality_points' => 0,
                'matchday_id' => $matchday->id,
                'team_id' => $team->id
            ]);
        }
        $score->matchday = $matchday;
        $score->team = $team;
        $score->compute();

        return $score;
    }
    
    public function loadDetails(Score $score)
    {
        return $this->loadInto($score, [
            'Lineups.Dispositions.Members' => [
                'Roles', 'Players', 'Clubs', 'Ratings' => function (Query $q) use ($score) {
                    return $q->where(['Ratings.matchday_id' => $score->matchday_id]);
                }
            ]
        ]);
    }
}
