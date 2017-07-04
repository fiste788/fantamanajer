<?php
namespace App\Model\Table;

use Cake\ORM\Association\HasMany;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Players Model
 *
 * @property HasMany $Members
 * @property HasMany $View0LineupsDetails
 * @property HasMany $View0Members
 * @property HasMany $View1MembersStats
 */
class PlayersTable extends Table
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

        $this->table('players');
        $this->displayField('surname');
        $this->primaryKey('id');

        $this->hasMany('Members', [
            'foreignKey' => 'player_id'
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
            ->allowEmpty('name');

        $validator
            ->requirePresence('surname', 'create')
            ->notEmpty('surname');

        return $validator;
    }
}
