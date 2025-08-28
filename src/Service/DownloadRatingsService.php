<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Matchday;
use Cake\Console\ConsoleIo;
use Cake\Http\Client;
use Cake\Log\Log;
use InvalidArgumentException;
use LogicException;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Service for downloading and decoding fantasy football ratings files.
 */
class DownloadRatingsService
{
    /**
     * @var \Cake\Console\ConsoleIo|null
     */
    public ?ConsoleIo $io = null;

    /** @var string */
    private const string DOWNLOAD_URL = 'https://maxigames.maxisoft.it/downloads.php';

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
        $year = $matchday->season->year;
        $folder = RATINGS_CSV . $year . DS;
        $number = str_pad((string)$matchday->number, 2, '0', STR_PAD_LEFT);
        $pathCsv = "{$folder}Matchday{$number}.csv";

        $filesystem = new Filesystem();

        $this->io?->out("Checking for existing file at {$pathCsv}");

        if ($filesystem->exists($pathCsv) && filesize($pathCsv) > 0 && !$forceDownload) {
            $this->io?->out('File already exists. Download not required.');

            return $pathCsv;
        }
        $this->io?->out('File not found or download forced. Starting download process.');

        $mxmFilePath = $this->downloadMxmFile($matchday->number + $offsetGazzetta, $matchday->season->year);
        if ($mxmFilePath !== null) {
            $decryptedContent = $this->decryptMXMFile($matchday, $mxmFilePath);
            $filesystem->remove($mxmFilePath);

            if ($decryptedContent !== null && strlen($decryptedContent) > 42000) {
                $this->writeCsvRatings($decryptedContent, $pathCsv);

                return $pathCsv;
            }
        }

        $this->io?->err('Failed to download or decrypt ratings.');

        return null;
    }

    /**
     * Downloads the ratings file and returns the path to the un-decrypted file.
     *
     * @param int $matchday The matchday number.
     * @return string|null The path to the downloaded .mxm file or null on error.
     */
    public function downloadMxmFile(int $matchday, int $seasonYear): ?string
    {
        $this->io?->out('Searching for ratings on maxigames.maxisoft.it');
        $http = new Client([
            'ssl_verify_peer' => false,
            'headers' => [
                'Connection' => 'keep-alive',
                'Accept' => '*/*',
                'Accept-Encoding' => 'gzip, deflate',
                'Cache-Control' => 'no-cache',
            ],
        ]);
        $response = $http->get(self::DOWNLOAD_URL);

        if (!$response->isOk()) {
            $this->io?->err('Error: Could not connect to maxigames.maxisoft.it');

            return null;
        }

        $crawler = new Crawler($response->getStringBody());
        $tr = $crawler->filter(".container .col-sm-9 tr:contains('Giornata {$matchday}')");

        if ($tr->count() === 0) {
            $this->io?->err('Error: Ratings not found for the specified matchday.');

            return null;
        }

        $this->io?->out('Ratings found for the matchday.');

        try {
            $link = $tr->selectLink('DOWNLOAD');
            if ($link->count()) {
                $url = $link->link()->getUri();
            } else {
                $button = $tr->selectButton('DOWNLOAD');

                /** @var array<string> $matches */
                $matches = sscanf($button->attr('onclick') ?? '', "window.open('%[^']");
                $url = $matches[0] ?? null;
            }

            if ($url === null) {
                $this->io?->err('Error: Could not find download link or button.');

                return null;
            }

            $this->io?->verbose('Downloading ' . $url);
            $response = $http->get($url);

            if (!$response->isOk()) {
                $this->io?->err('Error: File download failed.');

                return null;
            }

            $downloadUrl = $this->getDropboxUrl($response->getStringBody(), $url);

            if ($downloadUrl !== null) {
                 $filesystem = new Filesystem();
                $tempPath = TMP . $seasonYear . DS;
                $file = $tempPath . str_pad((string)$matchday, 2, '0', STR_PAD_LEFT) . '.mxm';

                if (!$filesystem->exists($tempPath)) {
                    $filesystem->mkdir($tempPath);
                }

                try {
                    $contentResponse = $http->get($downloadUrl);
                    if ($contentResponse->isOk()) {
                        $filesystem->dumpFile($file, $contentResponse->getStringBody());
                        $this->io?->verbose('File downloaded and saved to ' . $file);

                        return $file;
                    }
                } catch (IOException $e) {
                    $this->io?->err('Error saving file: ' . $e->getMessage());
                }
            }

            $this->io?->err('Could not get a direct download link from Dropbox.');

            return null;
        } catch (RuntimeException | InvalidArgumentException | LogicException $e) {
            Log::error('Error parsing download link: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Gets the direct download URL from a Dropbox page.
     *
     * @param string $dropboxPage The HTML body of the Dropbox page.
     * @param string $url The original URL of the Dropbox page.
     * @return string|null The direct download URL.
     */
    private function getDropboxUrl(string $dropboxPage, string $url): ?string
    {
        $crawler = new Crawler($dropboxPage);
        try {
            $button = $crawler->filter('#default_content_download_button');
            if ($button->count()) {
                return $button->attr('href');
            }
        } catch (RuntimeException | InvalidArgumentException | LogicException $e) {
            Log::error('Error parsing download link: ' . $e->getMessage());
        }

        return str_replace('www.dropbox', 'dl.dropboxusercontent', $url);
    }

    /**
     * Decrypts a .mxm file.
     *
     * @param \App\Model\Entity\Matchday $matchday The matchday.
     * @param string $path The path to the file to decrypt.
     * @return string|null The decrypted content.
     */
    private function decryptMXMFile(Matchday $matchday, string $path): ?string
    {
        $decryptKey = $matchday->season->key_gazzetta;
        if ($decryptKey === null) {
            $this->io?->err('Decoding key not available.');

            return null;
        }

        $content = file_get_contents($path);
        if ($content === false || empty($content)) {
            $this->io?->err('Could not read file content.');

            return null;
        }

        $this->io?->out('Starting file decoding.');
        $explodeXor = explode('-', $decryptKey);
        $body = '';
        $contentLength = strlen($content);
        $keyLength = count($explodeXor);

        for ($i = 0; $i < $contentLength; $i++) {
            $xor = (int)hexdec($explodeXor[$i % $keyLength]);
            $body .= chr(ord($content[$i]) ^ $xor);
        }

        return $body;
    }

    /**
     * Writes decrypted ratings into a CSV file.
     *
     * @param string $content The decrypted content.
     * @param string $path The path to the CSV file to write.
     * @return void
     */
    private function writeCsvRatings(string $content, string $path): void
    {
        $filesystem = new Filesystem();
        $lines = explode("\n", trim($content));
        $outputLines = [];

        foreach ($lines as $line) {
            $pieces = explode('|', $line);
            if (isset($pieces[4]) && $pieces[4] != '0') {
                $outputLines[] = implode(';', $pieces);
            }
        }

        $output = implode("\n", $outputLines);
        $filesystem->dumpFile($path, $output);
        $this->io?->out('CSV file created at ' . $path);
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
