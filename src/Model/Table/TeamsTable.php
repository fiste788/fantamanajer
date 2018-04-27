<?php

namespace App\Model\Table;

use App\Model\Entity\Team;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\Filesystem\File;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Spatie\Image\Image;

/**
 * Teams Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ChampionshipsTable|\Cake\ORM\Association\BelongsTo $Championships
 * @property \App\Model\Table\ArticlesTable|\Cake\ORM\Association\HasMany $Articles
 * @property \App\Model\Table\EventsTable|\Cake\ORM\Association\HasMany $Events
 * @property \App\Model\Table\LineupsTable|\Cake\ORM\Association\HasMany $Lineups
 * @property \App\Model\Table\ScoresTable|\Cake\ORM\Association\HasMany $Scores
 * @property \App\Model\Table\SelectionsTable|\Cake\ORM\Association\HasMany $Selections
 * @property \App\Model\Table\TransfertsTable|\Cake\ORM\Association\HasMany $Transferts
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsToMany $Members
 * @method \App\Model\Entity\Team get($primaryKey, $options = [])
 * @method \App\Model\Entity\Team newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Team[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Team|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Team patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Team[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Team findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Josegonzalez\Upload\Model\Behavior\UploadBehavior
 * @method \App\Model\Entity\Team|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @property \App\Model\Table\NotificationSubscriptionsTable|\Cake\ORM\Association\HasMany $PushNotificationSubscriptions
 * @property \App\Model\Table\NotificationSubscriptionsTable|\Cake\ORM\Association\HasMany $EmailNotificationSubscriptions
 */
class TeamsTable extends Table
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

        $this->setTable('teams');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior(
            'Josegonzalez/Upload.Upload',
            [
                'photo_data' => [
                    'path' => 'webroot{DS}files{DS}{table}{DS}{primaryKey}{DS}photo{DS}',
                    'fields' => [
                        'dir' => 'photo_dir', // defaults to `dir`
                        'size' => 'photo_size', // defaults to `size`
                        'type' => 'photo_type', // defaults to `type`
                    ],
                    'nameCallback' => function ($data, $settings) {
                        return strtolower($data['name']);
                    },
                    'transformer' => function (RepositoryInterface $table, EntityInterface $entity, $data, $field, $settings) {
                        $tmpFile = new File($data['name']);
                        $image = Image::load($data['tmp_name']);
                        $array = [$data['tmp_name'] => $data['name']];
                        foreach (Team::$size as $value) {
                            if ($value < $image->getWidth()) {
                                $tmp = tempnam(TMP, $value) . '.' . $tmpFile->ext();
                                $image->width($value)->save($tmp);
                                $array[$tmp] = $value . 'w' . DS . $data['name'];
                            }
                        }

                        return $array;
                    },
                    'deleteCallback' => function ($path, $entity, $field, $settings) {
                        // When deleting the entity, both the original and the thumbnail will be removed
                        // when keepFilesOnDelete is set to false
                        $array = [$path . $entity->{$field}];
                        foreach (Team::$size as $value) {
                            $array[] = $path . $value . DS . $entity->{$field};
                        }

                        return $array;
                    },
                    'keepFilesOnDelete' => false
                ],
            ]
        );

        $this->belongsTo(
            'Users',
            [
                'foreignKey' => 'user_id',
                'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Championships',
            [
                'foreignKey' => 'championship_id',
                'joinType' => 'INNER'
            ]
        );
        $this->hasMany(
            'Articles',
            [
                'foreignKey' => 'team_id'
            ]
        );
        $this->hasMany(
            'PushNotificationSubscriptions',
            [
                'className' => 'NotificationSubscriptions',
                'foreignKey' => 'team_id',
                'conditions' => ['type' => 'push'],
                'saveStrategy' => 'replace',
            ]
        );
        $this->hasMany(
            'EmailNotificationSubscriptions',
            [
                'className' => 'NotificationSubscriptions',
                'foreignKey' => 'team_id',
                'conditions' => ['type' => 'email'],
                'saveStrategy' => 'replace',
            ]
        );

        $this->hasMany(
            'Events',
            [
                'foreignKey' => 'team_id'
            ]
        );
        $this->hasMany(
            'Lineups',
            [
                'foreignKey' => 'team_id'
            ]
        );
        $this->hasMany(
            'Scores',
            [
                'foreignKey' => 'team_id'
            ]
        );
        $this->hasMany(
            'Selections',
            [
                'foreignKey' => 'team_id'
            ]
        );
        $this->hasMany(
            'Transferts',
            [
                'foreignKey' => 'team_id'
            ]
        );
        $this->belongsToMany(
            'Members',
            [
                'foreignKey' => 'team_id',
                'targetForeignKey' => 'member_id',
                'joinTable' => 'members_teams',
                'sort' => ['role_id']
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['championship_id'], 'Championships'));

        return $rules;
    }

    public function findByChampionshipId(Query $q, array $options)
    {
        return $q->contain(['Users'])
            ->where(['championship_id' => $options['championship_id']]);
    }
    
}
