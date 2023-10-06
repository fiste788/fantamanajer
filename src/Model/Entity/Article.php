<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Article Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string $body
 * @property \Cake\I18n\DateTime $created_at
 * @property \Cake\I18n\DateTime $modified_at
 * @property int $team_id
 * @property int $matchday_id
 *
 * @property \App\Model\Entity\Team $team
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
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'title' => true,
        'subtitle' => true,
        'body' => true,
        'created_at' => false,
        'modified_at' => false,
        'team_id' => true,
        'matchday_id' => true,
        'team' => true,
        'matchday' => true,
    ];

    /**
     * @param int $userId the user owner
     * @return bool
     */
    public function isOwnedBy(int $userId): bool
    {
        return $this->team->user_id == $userId;
    }
}
