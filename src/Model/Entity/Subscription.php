<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Subscription Entity
 *
 * @property int $id
 * @property string $endpoint
 * @property string $public_key
 * @property string $auth_token
 * @property int $user_id
 *
 * @property \App\Model\Entity\User $user
 */
class Subscription extends Entity
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
        'endpoint' => true,
        'user_id' => true,
        'user' => true
    ];
}
