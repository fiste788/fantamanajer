<?php
declare(strict_types=1);

namespace App\Model\Entity\Traits;

use Cake\Routing\Router;
use Symfony\Component\Filesystem\Filesystem;
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
     * @return null|string[]
     * @throws \Symfony\Component\Finder\Exception\DirectoryNotFoundException
     * @throws \Cake\Core\Exception\Exception
     * @throws \LogicException
     *
     * @psalm-return array<string, string>|null
     */
    private function _getPhotosUrl(string $path, string $baseUrl, ?string $name = null): ?array
    {
        $array = [];
        $filesystem = new Filesystem();
        if ($filesystem->exists($path)) {
            $name = $name ?? $this->id . '.jpg';
            $baseUrl = Router::url($baseUrl, true);
            $folder = new Finder();
            $folder->directories()->in($path);
            foreach ($folder->getIterator() as $sub) {
                $subpath = $sub->getFilename();
                if ($filesystem->exists($path . $subpath . DS . $name)) {
                    $array[$subpath] = $baseUrl . $subpath . '/' . rawurlencode($name);
                }
            }
            $principal = $path . $name;
            if ($filesystem->exists($principal)) {
                $size = getimagesize($principal);
                if ($size != false) {
                    $array[(string)$size[0] . 'w'] = $baseUrl . rawurlencode($name);
                }
            }
        }

        return empty($array) ? null : $array;
    }
}
