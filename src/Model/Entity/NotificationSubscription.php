<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NotificationSubscription Entity
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property bool $enabled
 * @property int $team_id
 *
 * @property \App\Model\Entity\Team $team
 */
class NotificationSubscription extends Entity
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
        'type' => true,
        'name' => true,
        'enabled' => true,
        'team_id' => true,
        'team' => true
    ];
}
