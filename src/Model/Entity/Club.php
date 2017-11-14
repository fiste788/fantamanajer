<?php

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\Routing\Router;
use const DS;

/**
 * Club Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $abbreviation
 * @property string $partitive
 * @property string $determinant
 * @property Member[] $members
 */
class Club extends Entity {

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

    protected function _getAbbreviation() {
        return strtoupper(substr($this->name, 0, 3));
    }

    protected function _getPhotoUrl() {
        return Router::url('/img/' . $this->getSource() . '/' . $this->id . '/photo/' . $this->id . '.png', true);
    }

    /*protected function _getBackgroundUrl() {
        if (file_exists(Configure::read('App.imagesPath.clubs') . $this->id . DS . 'background' . DS . $this->id . '.jpg')) {
            return Router::url('/img/' . $this->getSource() . '/' . $this->id . '/background/' . $this->id . '.jpg', true);
        }
    }*/

    protected function _getBackgroundUrl() {
        $array = [];
        $path = Configure::read('App.imagesPath.clubs') . $this->id . DS . 'background/';
        $baseUrl = Router::url('/img/' . $this->getSource() . '/' . $this->id . '/background/', true);
        $folder = new \Cake\Filesystem\Folder($path);
        $subfolders = $folder->subdirectories(null, false);
        foreach ($subfolders as $sub) {
            if (file_exists($path . $sub . DS . $this->id . '.jpg')) {
                $array[$sub] = $baseUrl . $sub . '/' . $this->id . '.jpg';
            }
        }
        $principal = $path . $this->id . '.jpg';
        if (file_exists($principal)) {
            $size = getimagesize($principal);
            $array[$size[0] . 'w'] = $baseUrl . $this->id . '.jpg';
        }
        if (!empty($array)) {
            return $array;
        }
    }

}
