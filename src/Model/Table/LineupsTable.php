<?php

namespace App\Model\Table;

use App\Model\Entity\Event as Event2;
use App\Model\Entity\Lineup;
use App\Model\Entity\Matchday;
use App\Model\Entity\Team;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

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
 * @method Lineup get($primaryKey, $options = [])
 * @method Lineup newEntity($data = null, array $options = [])
 * @method Lineup[] newEntities(array $data, array $options = [])
 * @method Lineup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method Lineup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method Lineup[] patchEntities($entities, array $data, array $options = [])
 * @method Lineup findOrCreate($search, callable $callback = null, $options = [])
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $Captain
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $VCaptain
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $VVCaptain
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
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
        $rules->add(
            function (Lineup $entity, $options) {
                if ($entity->jolly) {
                    $matchday = TableRegistry::get('Matchdays')->get($entity->matchday_id);
                    $matchdays = TableRegistry::get('Matchdays')
                    ->find()
                    ->where(['season_id' => $matchday->season_id])
                    ->count();

                    return TableRegistry::get('Lineups')->find()
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
            ['errorField' => 'jolly', 'message' => 'Hai già utilizzato il jolly']
        );

        return $rules;
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $events = TableRegistry::get('Events');
            $ev = $events->newEntity();
            $ev->type = Event2::NEW_LINEUP;
            $ev->team_id = $entity['team_id'];
            $ev->external = $entity['id'];
            $events->save($ev);
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

    public function findStatsByMatchdayAndTeam($matchday_id, $team_id)
    {
        return $this->find()
                ->contain(
                    [
                        'Teams',
                        'Dispositions' => [
                            'Members' => [
                                'Roles', 'Players', 'Clubs', 'Ratings' => function ($q) use ($matchday_id) {
                                    return $q->where(['matchday_id' => $matchday_id]);
                                }
                            ]
                        ]
                    ]
                )->where(['team_id' => $team_id, 'matchday_id' => $matchday_id])->first();
    }

    /**
     *
     * @param LineupsTable $matchday
     * @param Matchday                         $team
     * @param Team                              $team
     * @return Lineup
     */
    public function findLast(Matchday $matchday, Team $team)
    {
        return $this->find()
                ->innerJoinWith('Matchdays')
                ->contain(['Dispositions'])
                ->where([
                    'Lineups.team_id' => $team->id,
                    'Lineups.matchday_id <=' => $matchday->id,
                    'Matchdays.season_id' => $matchday->season_id
                ])
                ->order(['Matchdays.number' => 'DESC']);
    }

    /**
     *
     * @param LineupsTable $matchday
     * @param Matchday                      $team
     * @param Team                          $team
     * @return Lineup
     */
    public function findLastWithRatings(Matchday $matchday, Team $team)
    {
        return $this->findLast($matchday, $team)
                ->contain([
                    'Teams.Championships',
                    'Dispositions' => [
                        'Members' => function (Query $q) use ($matchday) {
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
                                    ['Ratings' => function (Query $q) use ($matchday) {
                                            return $q->where(['Ratings.matchday_id' => $matchday->id]);
                                    }
                                    ]
                                );
                        }
                    ]]);
    }
}
