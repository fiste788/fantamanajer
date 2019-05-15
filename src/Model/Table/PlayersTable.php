<?php
namespace App\Model\Table;

use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Players Model
 *
 * @property MembersTable|\Cake\ORM\Association\HasMany $Members
 * @property HasMany $View0LineupsDetails
 * @property HasMany $View0Members
 * @property HasMany $View1MembersStats
 * @method \App\Model\Entity\Player get($primaryKey, $options = [])
 * @method \App\Model\Entity\Player newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Player[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Player|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Player patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Player[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Player findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Player|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class PlayersTable extends Table
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

        $this->setTable('players');
        $this->setDisplayField('surname');
        $this->setPrimaryKey('id');

        $this->hasMany(
            'Members',
            [
                'foreignKey' => 'player_id'
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
            ->allowEmpty('name');

        $validator
            ->requirePresence('surname', 'create')
            ->notEmpty('surname');

        return $validator;
    }

    public function findWithDetails(Query $q, array $options): Query
    {
        return $q->contain(['Members' => function (Query $q) use ($options) {
            return $q->find('withDetails', ['championship_id' => $options['championship_id']]);
        }]);
    }
}
