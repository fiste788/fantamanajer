<?php

namespace App\Model\Entity;

use Cake\Filesystem\Folder;
use Cake\Routing\Router;

trait HasPhotoTrait
{
    /**
     *
     * @return array
     */
    private function _getPhotosUrl($path, $baseUrl, $name = null)
    {
        $array = [];
        if (!$name) {
            $name = $this->id . '.jpg';
        }
        $baseUrl = Router::url($baseUrl, true);
        $folder = new Folder($path);
        $subfolders = $folder->subdirectories(null, false);
        foreach ($subfolders as $sub) {
            if (file_exists($path . $sub . DS . $name)) {
                $array[$sub] = $baseUrl . $sub . '/' . $name;
            }
        }
        $principal = $path . $name;
        if (file_exists($principal)) {
            $size = getimagesize($principal);
            $array[$size[0] . 'w'] = $baseUrl . $name;
        }
        if (!empty($array)) {
            return $array;
        }
    }
}
