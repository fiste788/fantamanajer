<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Articles Model
 *
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\BelongsTo $Teams
 * @property \App\Model\Table\MatchdaysTable&\Cake\ORM\Association\BelongsTo $Matchdays
 *
 * @method \App\Model\Entity\Article get($primaryKey, $options = [])
 * @method \App\Model\Entity\Article newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Article[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Article|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Article[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Article findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Article|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticlesTable extends Table
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('articles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior(
            'Timestamp',
            [
                'events' => [
                    'Model.beforeSave' => [
                        'created_at' => 'new',
                        'modified_at' => 'always',
                    ],
                ],
            ]
        );

        $this->belongsTo(
            'Teams',
            [
                'foreignKey' => 'team_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Matchdays',
            [
                'foreignKey' => 'matchday_id',
                'joinType' => 'INNER',
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->allowEmpty('subtitle');

        $validator
            ->requirePresence('body', 'create')
            ->notEmpty('body');

        /* $validator
          ->dateTime('created_at')
          ->requirePresence('created_at', 'create')
          ->notEmpty('created_at'); */

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));

        return $rules;
    }

    /**
     * @inheritDoc
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options): void
    {
        /** @var \App\Model\Entity\Matchday $current */
        $current = $this->Matchdays->find('current')->first();
        $data['matchday_id'] = $current->id;
        $data->offsetUnset('created_at');
        $data->offsetUnset('modified_at');
    }

    /**
     * Find by championship query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findByChampionshipId(Query $q, array $options): Query
    {
        return $q->contain(['Teams' => [
            'fields' => ['id', 'name'],
        ]])->orderDesc('created_at')
            ->where(['championship_id' => $options['championship_id']]);
    }

    /**
     * Find by team query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findByTeamId(Query $q, array $options)
    {
        return $q->orderDesc('created_at')
            ->where(['team_id' => $options['team_id']]);
    }

    /**
     * @inheritDoc
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $event = new Event('Fantamanajer.newArticle', $this, [
                'article' => $entity,
            ]);
            EventManager::instance()->dispatch($event);
        }
    }
}
