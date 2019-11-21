<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * Club Entity
 *
 * @property int $id
 * @property string $name
 * @property string $partitive
 * @property string $determinant
 * @property string $abbreviation
 * @property string $photo_url
 * @property array $background_url
 *
 * @property \App\Model\Entity\Member[] $members
 */
class Club extends Entity
{
    use Traits\HasPhotoTrait;

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
        'partitive' => true,
        'determinant' => true,
        'members' => false,
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_size = [
        600,
        1280,
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_virtual = [
        'abbreviation',
        'photo_url',
        'background_url',
    ];

    /**
     *
     * @return string
     */
    protected function _getAbbreviation(): string
    {
        return strtoupper(substr($this->name, 0, 3));
    }

    /**
     *
     * @return string
     */
    protected function _getPhotoUrl(): string
    {
        return Router::url('/img/' . $this->getSource() . '/' . $this->id . '/photo/' . $this->id . '.png', true);
    }

    /**
     *
     * @return array
     */
    protected function _getBackgroundUrl(): array
    {
        $path = Configure::read('App.paths.images.clubs') . $this->id . DS . 'background/';

        return $this->_getPhotosUrl($path, '/img/' . $this->getSource() . '/' . $this->id . '/background/');
    }
}
