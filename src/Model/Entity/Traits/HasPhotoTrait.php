<?php
declare(strict_types=1);

namespace App\Model\Entity\Traits;

use Cake\Routing\Router;
use Symfony\Component\Finder\Finder;

trait HasPhotoTrait
{
    /**
     * Get photo
     *
     * @param string $path Path
     * @param string $baseUrl Url
     * @param string|null $name Name
     *
     * @return string[]
     */
    private function _getPhotosUrl(string $path, string $baseUrl, ?string $name = null): array
    {
        $array = [];
        if (!$name) {
            $name = $this->id . '.jpg';
        }
        $baseUrl = Router::url($baseUrl, true);
        $folder = new Finder();
        $folder->directories()->in($path);
        foreach ($folder->getIterator() as $sub) {
            if (file_exists($path . $sub->getRelativePath() . DS . $name)) {
                $array[$sub->getRelativePath()] = $baseUrl . $sub->getRelativePath() .
                    '/' . htmlspecialchars_decode($name);
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
