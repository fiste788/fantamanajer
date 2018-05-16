<?php

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * Club Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $abbreviation
 * @property string $partitive
 * @property string $determinant
 * @property \App\Model\Entity\Member[] $members
 * @property int $club_id
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \App\Model\Entity\View0Member[] $view0_members
 * @property \App\Model\Entity\View1MembersStat[] $view1_members_stats
 */
class Club extends Entity
{
    use HasPhotoTrait;

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
    protected $_size = [600, 1280];
    protected $_virtual = ['abbreviation', 'photo_url', 'background_url'];

    /**
     *
     * @return string
     */
    protected function _getAbbreviation()
    {
        return strtoupper(substr($this->name, 0, 3));
    }

    /**
     *
     * @return string
     */
    protected function _getPhotoUrl()
    {
        return Router::url('/img/' . $this->getSource() . '/' . $this->id . '/photo/' . $this->id . '.png', true);
    }

    /**
     *
     * @return array
     */
    protected function _getBackgroundUrl()
    {
        $path = Configure::read('App.paths.images.clubs') . $this->id . DS . 'background/';

        return $this->_getPhotosUrl($path, '/img/' . $this->getSource() . '/' . $this->id . '/background/');
    }
}
