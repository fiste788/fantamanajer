<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Clubs Model
 *
 * @property MembersTable|\Cake\ORM\Association\HasMany $Members
 *
 * @method \App\Model\Entity\Club get($primaryKey, $options = [])
 * @method \App\Model\Entity\Club newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Club[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Club|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Club patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Club[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Club findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Club|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class ClubsTable extends Table
{

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('clubs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany(
            'Members',
            [
                'foreignKey' => 'club_id',
                'sort' => ['role_id']
            ]
        );
        $this->hasMany(
            'View0LineupsDetails',
            [
                'foreignKey' => 'club_id'
            ]
        );
        $this->hasMany(
            'View0Members',
            [
                'foreignKey' => 'club_id'
            ]
        );
        $this->hasMany(
            'View1MembersStats',
            [
                'foreignKey' => 'club_id'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('partitive', 'create')
            ->notEmpty('partitive');

        $validator
            ->requirePresence('determinant', 'create')
            ->notEmpty('determinant');

        return $validator;
    }

    public function findBySeasonId(Query $q, array $options)
    {
        return $q->innerJoinWith('Members', function (Query $q) use ($options) {
            return $q->where(['season_id' => $options['season_id']]);
        })->group('Clubs.id')
            ->orderAsc('name');
    }
}
