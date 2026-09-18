<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Matchday;
use App\Service\Rating\MaxisoftSource;
use App\Service\Rating\PianetaFantaSource;
use App\Service\Rating\RatingSourceInterface;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;

/**
 * Service for downloading and decoding fantasy football ratings files.
 */
class DownloadRatingsService
{
    /**
     * @var \Cake\Console\ConsoleIo|null
     */
    public ?ConsoleIo $io = null;

    private RatingSourceInterface $source;

    public function __construct(?ConsoleIo $io = null)
    {
        $this->io = $io;

        // Legge la sorgente attiva, default su maxisoft per retrocompatibilità
        $provider = Configure::read('Ratings.provider', 'pianetafanta');

        $this->source = $provider === 'pianetafanta'
            ? new PianetaFantaSource()
            : new MaxisoftSource();

        $this->source->setIo($io);
    }

    /**
     * Gets the ratings for a specific matchday, downloading them if necessary.
     *
     * @param \App\Model\Entity\Matchday $matchday The matchday to get ratings for.
     * @param int $offsetGazzetta Optional offset for the matchday number.
     * @param bool $forceDownload Forces the download even if the file exists.
     * @return string|null The path to the CSV file or null on error.
     */
    public function getRatings(Matchday $matchday, int $offsetGazzetta = 0, bool $forceDownload = false): ?string
    {
        return $this->source->getRatings($matchday, $offsetGazzetta, $forceDownload);
    }

    /**
     * Downloads the ratings file and returns the path to the un-decrypted file.
     *
     * @param int $matchday The matchday number.
     * @return string|null The path to the downloaded .mxm file or null on error.
     */
    public function downloadMxmFile(int $matchday, int $seasonYear): ?string
    {
        if ($this->source instanceof MaxisoftSource) {
            return $this->source->downloadMxmFile($matchday, $seasonYear);
        }

        $this->io?->out('Skipping MXM download: current source does not utilize encrypted MXM files.');
        return null;
    }

    /**
     * Returns an associative array from a CSV file's content.
     *
     * @param string $path The file path.
     * @param non-empty-string $sep The column separator.
     * @param bool $header Indicates if the file has a header row.
     * @return array<string[]>
     */
    public function returnArray(string $path, string $sep = ';', bool $header = false): array
    {
        $arrayOk = [];
        $content = file_get_contents($path);
        if ($content !== false) {
            $array = explode("\n", trim($content));
            if ($header) {
                array_shift($array);
            }

            foreach ($array as $val) {
                $par = explode($sep, $val);
                if (isset($par[0])) {
                    $arrayOk[$par[0]] = $par;
                }
            }
        }

        return $arrayOk;
    }
}
