<?php
namespace App\Model\Entity;

use Authentication\IdentityInterface as AuthenticationIdentity;
use Authorization\IdentityInterface as AuthorizationIdentity;
use Cake\ORM\Entity;
use Cake\Utility\Hash;

/**
 * User Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property bool $active
 * @property bool $active_email
 * @property string $username
 * @property string $password
 * @property string $login_key
 * @property bool $admin
 * @property Team[] $teams
 * @property PushSubscription[] $push_subscriptions
 * @property int $old_id
 */
class User extends Entity implements AuthorizationIdentity, AuthenticationIdentity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'login_key'
    ];

    public function hasTeam($teamId)
    {
        return !empty(Hash::filter($this->teams, function (Team $value) use ($teamId) {
            return $value->id == $teamId;
        }));
    }

    public function isInChampionship($championshipId)
    {
        return !empty(Hash::filter($this->teams, function (Team $value) use ($championshipId) {
            return $value->championship_id == $championshipId;
        }));
    }
    
    public function isChampionshipAdmin($championshipId)
    {
        return !empty(Hash::filter($this->teams, function (Team $value) use ($championshipId) {
            return $value->championship_id == $championshipId && $value->admin;
        }));
    }

    /**
     * Authorization\IdentityInterface method
     */
    public function can($action, $resource)
    {
        return $this->authorization->can($this, $action, $resource);
    }

    /**
     * Authorization\IdentityInterface method
     */
    public function applyScope($action, $resource)
    {
        return $this->authorization->applyScope($this, $action, $resource);
    }

    /**
     * Authorization\IdentityInterface method
     */
    public function getOriginalData()
    {
        return $this;
    }

    /**
     * Setter to be used by the middleware.
     */
    public function setAuthorization($service)
    {
        $this->authorization = $service;

        return $this;
    }

    /**
     * Authentication\IdentityInterface method
     */
    public function getIdentifier()
    {
        return $this->id;
    }
}
