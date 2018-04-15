<?php
namespace App\Model\Entity;

use Authorization\IdentityInterface;
use Cake\ORM\Entity;
use Jose\Object\JWT;
use Security;

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
 * @property \App\Model\Entity\View2TeamsStat[] $view2_teams_stats
 * @property int $old_id
 */
class User extends Entity implements IdentityInterface
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
}
