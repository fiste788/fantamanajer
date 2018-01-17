<?php

namespace App\Model\Table;

use App\Model\Entity\Championship;
use App\Model\Entity\Matchday;
use App\Model\Entity\Score;
use App\Model\Entity\Season;
use App\Model\Entity\Team;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use PDOException;

/**
 * Scores Model
 *
 * @property TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property \Cake\ORM\Association\HasOne $Lineup
 * @property LineupsTable|\Cake\ORM\Association\BelongsTo $Lineups
 * @method Score get($primaryKey, $options = [])
 * @method Score newEntity($data = null, array $options = [])
 * @method Score[] newEntities(array $data, array $options = [])
 * @method Score|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method Score patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method Score[] patchEntities($entities, array $data, array $options = [])
 * @method Score findOrCreate($search, callable $callback = null, $options = [])
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

    public function findRankingDetails($championshipId)
    {
        $ranking = $this->findRanking($championshipId);

        $scores = $this->find(
            'all',
            ['contain' =>
                    ['Teams', 'Matchdays']
                ]
        )->matching(
            'Teams',
            function ($q) use ($championshipId) {
                    return $q->where(['Teams.championship_id' => $championshipId]);
            }
        );
        //->order('FIELD(Teams.id, ' . Hash::flatten($ranking, ",") . ')')->all();
        $result = [];
        $combined = [];
        foreach ($scores as $score) {
            $combined[$score->team->id][$score->matchday->id] = $score;
        }
        foreach ($ranking as $score) {
            $result[] = $combined[$score->team->id];
        }

        return $result;
    }

    /**
     *
     * @param int $championshipId
     * @return mixed
     */
    public function findRanking($championshipId)
    {
        $query = $this->find()
            ->contain(['Teams'])
            ->matching(
                'Teams',
                function ($q) use ($championshipId) {
                    return $q->where(['Teams.championship_id' => $championshipId]);
                }
            );

        return $query->select($this)->select(
            [
                    'sum_points' => $query->func()->sum('points')
                ]
        )->group('team_id')->orderDesc('sum_points')->all();
    }

    /**
     *
     * @param Season        $matchday
     * @param Matchday      $championship
     * @param Championship $championship
     * @return int
     */
    public function findAllPointsByMatchay(Matchday $matchday, Championship $championship)
    {
        $result = $this->find()
            ->where(['matchday_id <=' => $matchday->id])
            ->orWhere(['matchday_id' => null])
            ->matching(
                'Teams.Championships',
                function ($q) use ($championship) {
                    return $q->where(['championship_id' => $championship->id]);
                }
            )
            ->order(['team_id', 'matchday_id'])
            ->all();
        $classification = [];
        foreach ($result as $row) {
            $classification[$row->team_id][$row->matchday_id] = $row->points;
        }
        $sums = $this->findRankingByMatchday($championship, $matchday);
        //die("<pre>" . print_r($sums,1) . "</pre>");
        if ($sums) {
            foreach ($sums as $key => $val) {
                $sums[$key] = $classification[$key];
            }
        } else {
            $teamsReg = TableRegistry::get('Teams');
            $teams = $teamsReg->find('list')->where(['league_id' => $championship->id])->toArray();
            foreach ($teams as $key => $val) {
                $sums[$key][0] = 0;
            }
        }

        return $sums;
    }

    public function findRankingByMatchday($championship, $matchday)
    {
        $query = $this->find();
        $result = $query->select(
            [
                        'pointsTotal' => $query->func()->sum('points.points'),
                        'pointsAvg' => $query->func()->avg('points.real_points'),
                        'pointsMax' => $query->func()->max('points.real_points'),
                        'pointsMin' => $query->func()->min('points.real_points'),
                        'matchdays_wins' => 'COALESCE(vw1.matchdays_wins,0)',
                        'team_id' => 'points.team_id'
                    ]
        )
                ->hydrate(false)
                ->join(
                    [
                        'table' => 'vw_1_matchday_wins',
                        'alias' => 'vw1',
                        'type' => 'LEFT',
                        'conditions' => 'vw1.team_id = points.team_id',
                    ]
                )
                ->where(['points.matchday_id <=' => $matchday->id])
                ->matching(
                    'Teams.Championships',
                    function ($q) use ($championship) {
                        return $q->where(['championship_id' => $championship->id]);
                    }
                )
                ->group(['points.team_id'])->order(
                    [
                    'pointsTotal' => 'DESC',
                    'matchdays_wins' => 'DESC'
                    ]
                )->toArray();

        return Hash::combine($result, '{n}.team_id', '{n}');
        //->combine('team_id','pointsTotal')->toArray();
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
}
