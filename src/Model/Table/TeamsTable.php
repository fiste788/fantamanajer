<?php

namespace App\Model\Table;

use App\Model\Entity\Season;
use App\Model\Entity\User;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

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
            'photo' => [
                'path' => 'webroot{DS}files{DS}{model}{DS}{primaryKey}{DS}{field}{DS}',
                'fields' => [
                    // if these fields or their defaults exist
                    // the values will be set.
                    'dir' => 'photo_dir', // defaults to `dir`
                    'size' => 'photo_size', // defaults to `size`
                    'type' => 'photo_type', // defaults to `type`
                ],
                'nameCallback' => function ($data, $settings) {
                    return strtolower($data['name']);
                },
                /* 'transformer' =>  function ($table, $entity, $data, $field, $settings) {
                  $extension = pathinfo($data['name'], PATHINFO_EXTENSION);

                  // Store the thumbnail in a temporary file
                  $tmp = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                  // Use the Imagine library to DO THE THING
                  /*$size = new \Imagine\Image\Box(40, 40);
                  $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                  $imagine = new \Imagine\Gd\Imagine();

                  // Save that modified file to our temp file
                  $imagine->open($data['tmp_name'])
                  ->thumbnail($size, $mode)
                  ->save($tmp);

                  // Now return the original *and* the thumbnail
                  return [
                  $data['tmp_name'] => $data['name'],
                  //$tmp => 'thumbnail-' . $data['name'],
                  ];
                  }, */
                'deleteCallback' => function ($path, $entity, $field, $settings) {
                    // When deleting the entity, both the original and the thumbnail will be removed
                    // when keepFilesOnDelete is set to false
                    return [
                        $path . $entity->{$field},
                        $path . 'thumbnail-' . $entity->{$field}
                    ];
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
        $this->hasOne(
            'EmailSubscriptions',
            [
            'foreignKey' => 'team_id',
                'dependent' => true
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

    public function findCurrent(Query $query, array $options)
    {
        $matchdays = TableRegistry::get('Matchdays');
        $current = $matchdays->getCurrent();
        $query->matching(
            'Championships',
            function ($q) use ($current) {
                return $q->where(['Championships.season_id' => $current->season_id]);
            }
        );

        return $query;
    }

    /**
     *
     * @param User   $user
     * @param Season $season
     */
    public function findByUserAndSeason($user, $season)
    {
        return $this->find()->where(['user_id' => $user->id])
            ->matching(
                'Championships',
                function ($q) use ($season) {
                                return $q->where(['Championships.season_id' => $season->season_id]);
                }
            );
    }
}
