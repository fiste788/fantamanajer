<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Lineups Model
 *
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $Members
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $Members
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $Members
 * @property \App\Model\Table\MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property \App\Model\Table\TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property \App\Model\Table\DispositionsTable|\Cake\ORM\Association\HasMany $Dispositions
 * @property |\Cake\ORM\Association\HasMany $Scores
 * @property \App\Model\Table\View0LineupsDetailsTable|\Cake\ORM\Association\HasMany $View0LineupsDetails
 *
 * @method \App\Model\Entity\Lineup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Lineup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Lineup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Lineup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Lineup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Lineup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Lineup findOrCreate($search, callable $callback = null, $options = [])
 */
class LineupsTable extends Table
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

        $this->setTable('lineups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Captain', [
            'className' => 'Members',
            'foreignKey' => 'captain_id'
        ]);
        $this->belongsTo('VCaptain', [
            'className' => 'Members',
            'foreignKey' => 'vcaptain_id'
        ]);
        $this->belongsTo('VVCaptain', [
            'className' => 'Members',
            'foreignKey' => 'vvcaptain_id'
        ]);
        $this->belongsTo('Matchdays', [
            'foreignKey' => 'matchday_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Dispositions', [
            'foreignKey' => 'lineup_id',
            'sort' => ['Dispositions.position']
        ]);
        $this->hasOne('Scores', [
            'foreignKey' => 'lineup_id'
        ]);
        $this->hasMany('View0LineupsDetails', [
            'foreignKey' => 'lineup_id'
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
            ->requirePresence('module', 'create')
            ->notEmpty('module');

        $validator
            ->boolean('jolly')
            ->allowEmpty('jolly');

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
        $rules->add($rules->existsIn(['captain_id'], 'Members'));
        $rules->add($rules->existsIn(['vcaptain_id'], 'Members'));
        $rules->add($rules->existsIn(['vvcaptain_id'], 'Members'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        $rules->add($rules->existsIn(['team_id'], 'Teams'));

        return $rules;
    }
    
    public function findStatsByMatchdayAndTeam($matchday_id, $team_id)
	{
        $query = $this->find();
        return $query->contain([
            'Teams',
            'Dispositions' => [
                'Members' => [
                    'Players', 'Clubs', 'Ratings' => function ($q) use($matchday_id) {
                        return $q->where(['matchday_id' => $matchday_id]);
                    }
                ]
            ]
        ])->where(['team_id' => $team_id,'matchday_id' => $matchday_id])->first();
    }
}
