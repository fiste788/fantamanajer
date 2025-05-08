<?php
declare(strict_types=1);

namespace App\Model\Entity\Traits;

use Cake\Routing\Asset;
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
     * @return array<string>|null
     * @throws \Symfony\Component\Finder\Exception\DirectoryNotFoundException
     * @throws \Cake\Core\Exception\CakeException
     * @throws \LogicException
     * @psalm-return array<string, string>|null
     */
    private function _getPhotosUrl(string $path, string $baseUrl, ?string $name = null, ?string $ext = 'jpg'): ?array
    {
        $array = [];
        $filesystem = new Filesystem();
        if ($filesystem->exists($path)) {
            $name = $name ?? $this->id . '.' . $ext;
            // $baseUrl = Asset::imageUrl($baseUrl);
            $folder = new Finder();
            $folder->directories()->in($path);
            foreach ($folder->getIterator() as $sub) {
                $subpath = $sub->getFilename();
                if ($filesystem->exists($path . $subpath . DS . $name)) {
                    $array[$subpath] = Asset::imageUrl($baseUrl . $subpath . '/' . $name);
                }
            }
            $principal = $path . $name;
            if ($filesystem->exists($principal)) {
                $size = getimagesize($principal);
                if ($size != false) {
                    $array[(string)$size[0] . 'w'] = Asset::imageUrl($baseUrl . $name);
                }
            }
        }

        return empty($array) ? null : $array;
    }

    /**
     * Get size
     *
     * @return array<int|string, int|string>|false
     * @throws \Symfony\Component\Finder\Exception\DirectoryNotFoundException
     * @throws \Cake\Core\Exception\CakeException
     * @throws \LogicException
     */
    protected function _getSize(string $path, ?string $name = null, ?string $ext = 'jpg'): array|bool
    {
        $filesystem = new Filesystem();
        if ($filesystem->exists($path)) {
            $name = $name ?? $this->id . '.' . $ext;
            $principal = $path . $name;
            if ($filesystem->exists($principal)) {
                return getimagesize($principal);
            }
        }

        return false;
    }
}
