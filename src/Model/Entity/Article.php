<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Article Entity.
 *
 * @property int $id
 * @property string $title
 * @property string $subtitle
 * @property string $body
 * @property \Cake\I18n\Time $created_at
 * @property int $team_id
 * @property \App\Model\Entity\Team $team
 * @property int $matchday_id
 * @property \App\Model\Entity\Matchday $matchday
 */
class Article extends Entity
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

    function isOwnedBy($user_id)
    {
        return $this->team->user_id == $user_id;
    }
}
