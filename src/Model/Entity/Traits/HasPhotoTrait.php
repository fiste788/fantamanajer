<?php
declare(strict_types=1);

namespace App\Model\Entity\Traits;

use Cake\Filesystem\Folder;
use Cake\Routing\Router;

trait HasPhotoTrait
{
    /**
     * Get photo
     *
     * @param string $path Path
     * @param string $baseUrl Url
     * @param string|null $name Name
     * @return array
     */
    private function _getPhotosUrl(string $path, string $baseUrl, ?string $name = null): array
    {
        $array = [];
        if (!$name) {
            $name = $this->id . '.jpg';
        }
        $baseUrl = Router::url($baseUrl, true);
        $folder = new Folder($path);
        $subfolders = $folder->subdirectories($path, false);
        foreach ($subfolders as $sub) {
            if (file_exists($path . $sub . DS . $name)) {
                $array[$sub] = $baseUrl . $sub . '/' . str_replace(' ', '%20', $name);
            }
        }
        $principal = $path . $name;
        if (file_exists($principal)) {
            $size = getimagesize($principal);
            $array[$size[0] . 'w'] = $baseUrl . str_replace(' ', '%20', $name);
        }

        return $array;
    }
}
