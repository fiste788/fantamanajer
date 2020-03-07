<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;
use const DIRECTORY_SEPARATOR as DS;

/**
 * Member Entity
 *
 * @property int $id
 * @property int $code_gazzetta
 * @property bool $active
 * @property bool $playmaker
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $modified_at
 * @property int $player_id
 * @property int $role_id
 * @property int $club_id
 * @property int $season_id
 * @property string|null $photo_url
 * @property string|null $background_url
 * @property mixed|null $likely_lineup
 *
 * @property \App\Model\Entity\Player $player
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\Club $club
 * @property \App\Model\Entity\Season $season
 * @property \App\Model\Entity\Disposition[] $dispositions
 * @property \App\Model\Entity\Rating[] $ratings
 * @property \App\Model\Entity\Team[] $teams
 * @property \App\Model\Entity\MembersStat|null $stats
 */
class Member extends Entity
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
        'code_gazzetta' => false,
        'active' => false,
        'playmaker' => false,
        'created_at' => false,
        'modified_at' => false,
        'player_id' => false,
        'role_id' => false,
        'club_id' => false,
        'season_id' => false,
        'player' => false,
        'role' => false,
        'club' => false,
        'season' => false,
        'dispositions' => false,
        'ratings' => false,
        'teams' => false,
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_virtual = [
        'photo_url',
        'background_url',
    ];

    /**
     * Get photo url
     *
     * @return string|null
     * @throws \Cake\Core\Exception\Exception
     */
    protected function _getPhotoUrl(): ?string
    {
        if (file_exists(IMG_PLAYERS . 'season-' . $this->season_id . DS . $this->code_gazzetta . '.jpg')) {
            return Router::url(
                '/img/players/season-' . $this->season_id . '/' . $this->code_gazzetta . '.jpg',
                true
            );
        }

        return null;
    }

    /**
     * Get background url
     *
     * @return string|null
     * @throws \Cake\Core\Exception\Exception
     */
    protected function _getBackgroundUrl(): ?string
    {
        if (file_exists(IMG_PLAYERS . $this->club_id . DS . 'background' . DS . $this->club_id . '.jpg')) {
            return Router::url(
                '/img/Clubs/' . $this->club_id . '/background/' . $this->club_id . '.jpg',
                true
            );
        }

        return null;
    }
}
