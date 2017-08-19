<?php
namespace App\Model\Table;

use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Members Model
 *
 * @property BelongsTo $Players
 * @property BelongsTo $Roles
 * @property BelongsTo $Clubs
 * @property BelongsTo $Seasons
 * @property HasMany $Dispositions
 * @property HasMany $Ratings
 * @property HasMany $View0LineupsDetails
 * @property HasMany $VwMembersStats
 * @property BelongsToMany $Teams
 */
class MembersTable extends Table
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

        $this->table('members');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Players', [
            'foreignKey' => 'player_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Clubs', [
            'foreignKey' => 'club_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Seasons', [
            'foreignKey' => 'season_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Dispositions', [
            'foreignKey' => 'member_id'
        ]);
        $this->hasMany('Ratings', [
            'foreignKey' => 'member_id'
        ]);
        $this->belongsToMany('Teams', [
            'foreignKey' => 'member_id',
            'targetForeignKey' => 'team_id',
            'joinTable' => 'members_teams'
        ]);
        $this->hasOne('VwMembersStats', [
            'foreignKey' => 'member_id',
            'propertyName' => 'stats'
        ]);
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
            ->integer('code_gazzetta')
            ->requirePresence('code_gazzetta', 'create')
            ->notEmpty('code_gazzetta');

        $validator
            ->integer('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

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
        $rules->add($rules->existsIn(['player_id'], 'Players'));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));
        return $rules;
    }
    
    public function findWithStats(Query $query, array $options)
    {
        /*return $query
                ->select($this)
                ->select(['sum_present' => $query->func()->count('Ratings.present')])
                ->contain(['Ratings'])
                ->group('Members.id');*/
        
        return $query->hydrate(false)
                ->enableAutoFields(true)
                ->select([
                    'Members.club_id',
                    'stats.sum_present',
                    'stats.sum_valued',
                    'stats.avg_points',
                    'stats.avg_rating',
                    'stats.sum_goals',
                    'stats.sum_goals_against',
                    'stats.sum_assist',
                    'stats.sum_yellow_card',
                    'stats.sum_red_card'
                ])
                ->join([
                    'table' => 'vw_members_stats',
                    'alias' => 'stats',
                    'type' => 'INNER',
                    'conditions' => 'stats.member_id = Members.id',
                ])->group('Members.id');
    }
    
    public function findWithStats2(Query $query, array $options)
    {
        return $query->select(['sum_valued' => $query->func()->count('Ratings.valued')], false)
                ->enableAutoFields()
                ->innerJoinWith('Ratings')
                ->group('Members.id');
    }
    
    public function findFree($championshipId) 
    {
        $membersTeams = TableRegistry::get('MembersTeams');
        $ids = $membersTeams->find()
                ->select(['member_id'])
                ->matching('Teams', function($q) use ($championshipId) {
			return $q->where(['Teams.championship_id' => $championshipId]);
		});
        return $this->find('all')
                ->contain(['Players','Clubs','Roles','VwMembersStats'])
                /*->where(function ($exp, $q) use ($ids) {
                    die(var_dump($ids));
                    return $exp->notIn('Members.id', $ids);
                })*/
                ->where(['Members.id NOT IN' => $ids])
                ->where(['Members.active' => true])
                ->orderAsc('Players.surname')
                ->orderAsc('Players.name');
    }
}
