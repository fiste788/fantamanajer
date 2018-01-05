<?php
namespace App\Model\Entity;

use App\Model\Entity\Subscription;
use App\Model\Entity\Team;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

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
 * @property \App\Model\Entity\Team[] $teams
 * @property \App\Model\Entity\Subscription[] $subscriptions
 * @property \App\Model\Entity\View2TeamsStat[] $view2_teams_stats
 * @property int $old_id
 */
class User extends Entity
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

    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }
}
