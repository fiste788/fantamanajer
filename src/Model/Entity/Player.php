<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;
use const DIRECTORY_SEPARATOR as DS;

/**
 * Player Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string $surname
 * @property string $full_name
 * @property string|null $photo_url
 *
 * @property \App\Model\Entity\Member[] $members
 */
class Player extends Entity
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
        'name' => true,
        'surname' => true,
        'members' => false,
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_virtual = [
        'photo_url',
        'full_name',
    ];

    /**
     * Get photo url
     *
     * @return string|null
     */
    protected function _getPhotoUrl(): ?string
    {
        if ($this->members) {
            foreach ($this->members as $member) {
                if (file_exists(IMG_PLAYERS . 'season-' . $member->season->id . DS . $member->code_gazzetta . '.jpg')) {
                    return Router::url(
                        '/img/players/season-' . $member->season->id . '/' . $member->code_gazzetta . '.jpg',
                        true
                    );
                }
            }
        }

        return null;
    }

    /**
     * Get full name
     *
     * @return string
     */
    protected function _getFullName(): string
    {
        return $this->surname . ($this->name ? (' ' . $this->name) : '');
    }
}
