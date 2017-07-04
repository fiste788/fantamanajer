<?php
namespace App\Model\Table;

use App\Model\Entity\Score;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Scores Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Teams
 * @property \Cake\ORM\Association\BelongsTo $Matchdays
 * @property \Cake\ORM\Association\HasOne $Lineup
 */
class ScoresTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('scores');
        $this->displayField('id');
        $this->primaryKey('id');
        
        $this->belongsTo('Lineups', [
            'foreignKey' => 'lineup_id'
        ]);
        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Matchdays', [
            'foreignKey' => 'matchday_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
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
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
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
    public function findMatchdayWithPoints($season) 
	{
        $query = $this->find();
        $res = $query->hydrate(false)
                ->join([
                    'table' => 'matchdays',
                    'alias' => 'm',
                    'type' => 'LEFT',
                    'conditions' => 'm.id = scores.matchday_id',
                ])
                ->select(['matchday_id' => $query->func()->max('scores.matchday_id'),])
                ->where(['m.season_id' => $season->id])
                ->first();
        return $res['matchday_id'];
    }
    
    public function findRankingDetailsByChampionshipId($championshipId) {
        $scores = $this->find('all', ['contain' => 
                            ['Teams', 'Matchdays']
                        ])->matching('Teams', function($q) use ($championshipId) {
                            return $q->where(['Teams.championship_id' => $championshipId]);
                        })->all();
        $combined = [];
        foreach ($scores as $score) {
            $combined[$score->team->id][$score->matchday->id] = $score;
        }
        return $combined;
    }
    
    /**
     * 
     * @param type $championshipId
     * @return Query
     */
    public function findRankingByChampionshipId($championshipId) {
        $query = $this->find('all', [
            'contain' => ['Teams']
        ])->matching('Teams', function($q) use ($championshipId) {
            return $q->where(['Teams.championship_id' => $championshipId]);
        });
        return $query->select($this)->select([
            'sum_points' => $query->func()->sum('points')
        ])->group('team_id')->all();
    }

    /**
     * 
     * @param Season $season
     * @param Matchday $matchday
     * @param Championships $championship
     * @return int
     */
    public function getAllPointsByMatchay($matchday, $championship) 
	{
        $result = $this->find()
                ->where(['matchday_id <=' => $matchday->id])
                ->orWhere(['matchday_id' => NULL])
                ->matching('Teams.Championships', function($q) use($championship) {
                    return $q->where(['championship_id' => $championship->id]);
                })
                ->order(['team_id','matchday_id'])
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
        $result = $query->select([
                    'pointsTotal' => $query->func()->sum('points.points'),
                    'pointsAvg' => $query->func()->avg('points.real_points'),
                    'pointsMax' => $query->func()->max('points.real_points'),
                    'pointsMin' => $query->func()->min('points.real_points'),
                    'matchdays_wins' => 'COALESCE(vw1.matchdays_wins,0)',
                    'team_id' => 'points.team_id'
                ])
                ->hydrate(false)
                ->join([
                    'table' => 'vw_1_matchday_wins',
                    'alias' => 'vw1',
                    'type' => 'LEFT',
                    'conditions' => 'vw1.team_id = points.team_id',
                ])
                ->where(['points.matchday_id <=' => $matchday->id])
                ->matching('Teams.Championships', function($q) use($championship) {
                    return $q->where(['championship_id' => $championship->id]);
                })
                //->andWhere(['points.league_id' => $league->id])
                ->group(['points.team_id'])->order([
                    'pointsTotal' => 'DESC',
                    'matchdays_wins' => 'DESC'
                ])->toArray();
                return Hash::combine($result, '{n}.team_id','{n}');
                //->combine('team_id','pointsTotal')->toArray();
    }
}
