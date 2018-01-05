<?php

namespace App\Model\Table;

use App\Model\Entity\Disposition;
use App\Model\Entity\Lineup;
use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use App\Model\Entity\Team;
use Cake\ORM\Association\HasOne;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use PDOException;

/**
 * Scores Model
 *
 * @property \App\Model\Table\TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property \App\Model\Table\MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property HasOne $Lineup
 * @property \App\Model\Table\LineupsTable|\Cake\ORM\Association\BelongsTo $Lineups
 * @method \App\Model\Entity\Score get($primaryKey, $options = [])
 * @method \App\Model\Entity\Score newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Score[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Score|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Score patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Score[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Score findOrCreate($search, callable $callback = null, $options = [])
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
        $query = $this->find(
            'all',
            [
                    'contain' => ['Teams']
            ]
        )->matching(
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
     * @param Championships $championship
     * @return int
     */
    public function getAllPointsByMatchay($matchday, $championship)
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
        $sums = $this->getClassificationByMatchday($championship, $matchday);
        //die("<pre>" . print_r($sums,1) . "</pre>");
        if (isset($sums)) {
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

    public function getClassificationByMatchday($championship, $matchday)
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
                        //->andWhere(['points.league_id' => $league->id])
                        ->group(['points.team_id'])->order(
                            [
                            'pointsTotal' => 'DESC',
                            'matchdays_wins' => 'DESC'
                            ]
                        )->toArray();

        return Hash::combine($result, '{n}.team_id', '{n}');
        //->combine('team_id','pointsTotal')->toArray();
    }

    protected function substitution($member, $notRegular)
    {
        foreach ($notRegular as $disposition) {
            $giocatorePanchina = $disposition->member;
            $voto = $giocatorePanchina->ratings[0];
            if (($member->role_id == $giocatorePanchina->role_id) && ($voto->valued)) {
                return $disposition->id;
            }
            //die($member);
        }

        return null;
    }

    public function getCaptainActive(Lineup $lineup)
    {
        $captains = [];
        $captains[] = $lineup->captain_id;
        $captains[] = $lineup->vcaptain_id;
        $captains[] = $lineup->vvcaptain_id;
        foreach ($captains as $cap) {
            if (!is_null($cap) && $cap != "") {
                $dispositions = array_filter(
                    $lineup->dispositions,
                    function ($value) use ($cap) {
                        return $value->member_id == $cap;
                    }
                );
                $disposition = array_shift($dispositions);
                if ($disposition && $disposition->member->ratings[0]->present) {
                    return $cap;
                }
            }
        }

        return null;
    }

    /**
     *
     * @param Team     $team
     * @param Matchday $matchday
     * @return int
     * @throws PDOException
     */
    public function calculate(Team $team, Matchday $matchday)
    {
        $lineups = TableRegistry::get('Lineups');
        $scores = TableRegistry::get('Scores');
        $lineup = $this->getLastLineup($lineups, $matchday, $team);
        $score = $scores->findByTeamIdAndMatchdayId($team->id, $matchday->id);
        $championship = $team->championship;
        if ($score->isEmpty()) {
            $score = $this->newEntity();
            $score->matchday_id = $matchday->id;
            $score->team_id = $team->id;
        }
        if ($lineup == null || ($lineup->matchday_id != $matchday->id && $championship->points_missed_lineup == 0)) {
            $score->set('real_points', 0);
            $score->set('points', 0);
            $scores->save($score);
        } else {
            if ($lineup->matchday_id != $matchday->id) {
                $lineup->jolly = null;
                $lineup->matchday_id = $matchday->id;
                $lineup->team_id = $team->id;
                if (!$championship->captain_missed_lineup) {
                    $lineup->captain_id = null;
                    $lineup->vcaptain_id = null;
                    $lineup->vvcaptain_id = null;
                }
                //$formazione = clone $formazione;
                $lineup = $lineups->newEntity(
                    $lineup->toArray(),
                    ['associated' => [
                        'Dispositions' => ['associated' => ['Members' => ['associated' => ['Ratings']]]]
                    ]]
                );
                $lineup->id = null;
                foreach ($lineup->dispositions as $key => $val) {
                    $val->consideration = 0;
                    unset($val->id);
                    unset($val->lineup_id);
                    $lineup->dispositions[$key] = $val;
                }
            }

            $cap = 0;
            $sum = 0;

            if ($championship->captain) {
                $cap = $this->getCaptainActive($lineup);
            }
            //die("aaa " . $cap);
            $notRegular = $lineup->dispositions;
            array_splice($notRegular, 0, 11);
            $entering = [];
            foreach ($lineup->dispositions as $disposition) {
                if ($disposition->position <= 11) {
                    $member = $disposition->member;
                    if ((!$member->active || !$member->ratings[0]->valued) && count($entering) <= 3) {
                        $substitution = $this->substitution($member, $notRegular);
                        if ($substitution != null) {
                            $entering[$substitution] = true;
                        }
                    } else {
                        $sum += $this->playerIn($disposition, $cap);
                    }
                } else {
                    if (array_key_exists($disposition->id, $entering)) {
                        $sum += $this->playerIn($disposition, $cap);
                    }
                }
                $lineup->dirty('dispositions', true);
            }

            $lineups->save($lineup, ['associated' => ['Dispositions' => ['associated' => false]]]);
            $score->real_points = $sum;
            if ($lineup->jolly) {
                $sum *= 2;
            }
            $score->points = $sum;
            $score->lineup_id = $lineup->id;
            if ($championship->points_missed_lineup != 100 && $matchday->id != $lineup->matchday_id) {
                $puntiDaTogliere = round((($sum / 100) * (100 - $championship->points_missed_lineup)), 1);
                $modulo = ($puntiDaTogliere * 10) % 5;
                $score->penality_points = -(($puntiDaTogliere * 10) - $modulo) / 10;
                $score->penality = 'Formazione non settata';
                $score->points = $score->points - $score->penality_points;
            }
            $this->save($score);
        }

        return $score->points;
    }

    private static function playerIn(Disposition $disposition, $cap)
    {
        $member = $disposition->member;
        $disposition->consideration = 1;
        $points = $member->ratings[0]->points_no_bonus;
        if ($cap && $member->id == $cap) {
            $disposition->consideration = 2;
            $points *= 2;
        }

        return $points;
    }

    /**
     *
     * @param \App\Model\Table\LineupsTable $lineups
     * @param Matchday                      $matchday
     * @param Team                          $team
     * @return Lineup
     */
    public static function getLastLineup(LineupsTable $lineups, Matchday $matchday, Team $team)
    {
        return $lineups->find()
            ->innerJoinWith('Matchdays')
            ->contain(
                ['Dispositions' => ['Members' => function (Query $q) use ($matchday) {
                                    return $q->find(
                                        'list',
                                        [
                                                'keyField' => 'id',
                                                'valueField' => function ($obj) {
                                                    return $obj;
                                                }]
                                    )->contain(
                                        ['Ratings' => function (Query $q) use ($matchday) {
                                                    return $q->where(['Ratings.matchday_id' => $matchday->id]);
                                        }]
                                    );
                }
                            ]]
            )
                        ->where(['Lineups.team_id' => $team->id, 'Lineups.matchday_id <=' => $matchday->id, 'Matchdays.season_id' => $matchday->season->id])
                        ->order(['Lineups.matchday_id' => 'DESC'])->first();
    }
}
