<?php
namespace App\Model\Table;

use App\Model\Entity\Lineup;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Lineups Model
 *
 * @property MembersTable|BelongsTo $Members
 * @property MembersTable|BelongsTo $Members
 * @property MembersTable|BelongsTo $Members
 * @property MatchdaysTable|BelongsTo $Matchdays
 * @property TeamsTable|BelongsTo $Teams
 * @property DispositionsTable|HasMany $Dispositions
 * @property |HasMany $Scores
 * @property \App\Model\Table\View0LineupsDetailsTable|HasMany $View0LineupsDetails
 *
 * @method Lineup get($primaryKey, $options = [])
 * @method Lineup newEntity($data = null, array $options = [])
 * @method Lineup[] newEntities(array $data, array $options = [])
 * @method Lineup|bool save(EntityInterface $entity, $options = [])
 * @method Lineup patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Lineup[] patchEntities($entities, array $data, array $options = [])
 * @method Lineup findOrCreate($search, callable $callback = null, $options = [])
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
        
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'modified_at' => 'always'
                ]
            ]
        ]);

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
            'sort' => ['Dispositions.position'],
            'saveStrategy' => 'replace'
        ]);
        $this->hasOne('Scores', [
            'foreignKey' => 'lineup_id'
        ]);
        $this->hasMany('View0LineupsDetails', [
            'foreignKey' => 'lineup_id'
        ]);
    }
    
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {
        if(isset($data['created_at'])) {
            unset($data['created_at']);
        }
        if(isset($data['modified_at'])) {
            unset($data['modified_at']);
        }
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
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

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['captain_id'], 'Captain'));
        $rules->add($rules->existsIn(['vcaptain_id'], 'VCaptain'));
        $rules->add($rules->existsIn(['vvcaptain_id'], 'VVCaptain'));
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
                    'Roles', 'Players', 'Clubs', 'Ratings' => function ($q) use($matchday_id) {
                        return $q->where(['matchday_id' => $matchday_id]);
                    }
                ]
            ]
        ])->where(['team_id' => $team_id,'matchday_id' => $matchday_id])->first();
    }
}
