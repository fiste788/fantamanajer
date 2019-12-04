<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Matchday;
use Cake\Console\ConsoleIo;
use Cake\Http\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Undocumented class
 */
class DownloadRatingsService
{
    /**
     *
     * @var \Cake\Console\ConsoleIo|null
     */
    private $io;

    /**
     * Undocumented function
     *
     * @param \Cake\Console\ConsoleIo $io IO
     */
    public function __construct(ConsoleIo $io)
    {
        $this->io = $io;
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param int $offsetGazzetta Offset
     * @param bool $forceDownload Force download
     * @return string|null
     */
    public function getRatings(Matchday $matchday, $offsetGazzetta = 0, $forceDownload = false): ?string
    {
        $year = $matchday->season->year;
        $folder = RATINGS_CSV . $year;
        $pathCsv = $folder . DS . "Matchday" . str_pad((string)$matchday->number, 2, "0", STR_PAD_LEFT) . ".csv";
        $filesystem = new Filesystem();
        if ($this->io != null) {
            $this->io->out("Search file in path " . $pathCsv);
        }
        if ($filesystem->exists($pathCsv) && filesize($pathCsv) > 0 && !$forceDownload) {
            return $pathCsv;
        } else {
            $file = TMP . 'mcc' . str_pad((string)$matchday->number, 2, "0", STR_PAD_LEFT) . '.mxm';
            if ($this->io != null) {
                $this->io->verbose($file);
            }
            if ($forceDownload || !file_exists($file)) {
                return $this->downloadRatings($matchday, $pathCsv, ($matchday->number + $offsetGazzetta));
            } else {
                return $this->downloadRatings($matchday, $pathCsv, ($matchday->number + $offsetGazzetta), $file);
            }
        }
    }

    /**
     * Download ratings
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param string $path Path
     * @param int $matchdayGazzetta Matchday gazzetta
     * @param string $url Url
     * @return string|null
     */
    private function downloadRatings(
        Matchday $matchday,
        string $path,
        int $matchdayGazzetta,
        ?string $url = null
    ): ?string {
        $url = $url ?? $this->getRatingsFile($matchdayGazzetta);
        if (!empty($url)) {
            $content = $this->decryptMXMFile($matchday, $url);
            if (!empty($content)) {
                $this->writeCsvRatings($content, $path);
                //self::writeXmlVoti($content, $percorsoXml);
                return $path;
            }
        }

        return null;
    }

    /**
     * Write csv ratings
     *
     * @param string $content Content
     * @param string $path Path
     * @return void
     */
    public function writeCsvRatings(string $content, string $path): void
    {
        $lines = explode("\n", $content);
        array_pop($lines);
        foreach ($lines as $key => $val) {
            $pieces = explode("|", $val);
            $lines[$key] = join(";", $pieces);
            if ($pieces[4] == 0) {
                unset($lines[$key]);
            }
        }
        $output = join("\n", $lines);
        if ($this->io != null) {
            $this->io->createFile($path, $output);
        } else {
            (new Filesystem())->dumpFile($path, $output);
        }
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param string $path Path
     * @return string|null
     */
    public function decryptMXMFile(Matchday $matchday, ?string $path = null): ?string
    {
        $decrypt = $matchday->season->key_gazzetta;
        if ($path != null && $decrypt != null) {
            if ($this->io != null) {
                $this->io->out("Starting decrypt " . $path);
            }
            $p_file = fopen($path, "r");
            if ($p_file) {
                $body = "";
                $explode_xor = explode("-", $decrypt);
                $i = 0;
                $content = file_get_contents($path);
                if (!empty($content)) {
                    while (!feof($p_file)) {
                        if ($i == count($explode_xor)) {
                            $i = 0;
                        }

                        $line = fgets($p_file, 2);
                        if ($line) {
                            $xor2 = (int)(hexdec(bin2hex($line)) ^ hexdec($explode_xor[$i]));
                            $body .= chr($xor2);
                        } else {
                            if ($this->io != null) {
                                $this->io->out("salto " . substr($body, -5));
                            }
                        }
                        $i++;
                    }
                }
                fclose($p_file);

                return $body;
            }
        }

        return null;
    }

    /**
     * Get ratings file
     *
     * @param int $matchday Matchday
     * @return string|null
     */
    public function getRatingsFile(int $matchday): ?string
    {
        if ($this->io != null) {
            $this->io->out("Search ratings on maxigames");
        }
        $http = new Client();
        $http->setConfig('headers', [
            'Connection' => 'keep-alive',
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip, deflate',
            'Cache-Control' => 'no-cache',
        ]);
        $url = "https://maxigames.maxisoft.it/downloads.php";
        if ($this->io != null) {
            $this->io->verbose("Downloading " . $url);
        }
        $response = $http->get($url);
        if ($response->isOk()) {
            if ($this->io != null) {
                $this->io->out("Maxigames found");
            }
            $crawler = new Crawler();
            $crawler->addContent($response->getStringBody());
            $tr = $crawler->filter(".container .col-sm-9 tr:contains('Giornata $matchday')");
            if ($tr->count() > 0) {
                if ($this->io != null) {
                    $this->io->out("Ratings found for matchday");
                }
                $url = $this->getUrlFromMatchdayRow($tr);

                if ($url != null) {
                    return $this->downloadDropboxUrl($url, $matchday, $http);
                }
            }
        }

        return null;
    }

    /**
     * Get url From row
     *
     * @param \Symfony\Component\DomCrawler\Crawler $tr Row
     * @return string|null
     */
    private function getUrlFromMatchdayRow(Crawler $tr): ?string
    {
        $button = $tr->selectButton("DOWNLOAD");
        if (!$button->count()) {
            $link = $tr->selectLink("DOWNLOAD");
            if ($link->count()) {
                return $link->link()->getUri();
            }
        } else {
            $matches = sscanf($button->attr("onclick") ?? "", "window.open('%[^']");
            //preg_match("/window.open\(\'(.*?)\'#is/",,$matches);
            return $matches[0];
        }

        return null;
    }

    /**
     * Download dropbox url
     *
     * @param string $url Url
     * @param int $matchday Matchday
     * @param \Cake\Http\Client $http Client
     * @return string|null
     */
    private function downloadDropboxUrl(string $url, int $matchday, Client $http): ?string
    {
        if ($url) {
            if ($this->io != null) {
                $this->io->verbose("Downloading " . $url);
            }
            $response = $http->get($url);
            if ($this->io != null) {
                $this->io->verbose("Response " . $response->getStatusCode());
            }
            if ($response->isOk()) {
                $crawler = new Crawler();
                $crawler->addContent($response->getStringBody());
                $button = $crawler->filter("#default_content_download_button");
                if ($button->count()) {
                    $url = $button->attr("href");
                } else {
                    $url = str_replace("www", "dl", $url);
                }
                if ($this->io != null) {
                    $this->io->out("Downloading $url in tmp dir");
                }
                $file = TMP . $matchday . '.mxm';
                file_put_contents($file, file_get_contents($url ?? ""));

                return $file;
            } else {
                if ($this->io != null) {
                    $this->io->err('Response not ok');
                }
            }
        }

        return null;
    }

    /**
     * Return array
     *
     * @param string $path Path
     * @param string $sep Sep
     * @param bool $header Header
     *
     * @return string[][]
     */
    public function returnArray(string $path, string $sep = ";", bool $header = false): array
    {
        $arrayOk = [];
        $content = file_get_contents($path);
        if ($content != false) {
            $array = explode("\n", trim($content));
            if ($header) {
                array_shift($array);
            }

            foreach ($array as $val) {
                $par = explode($sep, $val);
                if ($par != false) {
                    $arrayOk[$par[0]] = $par;
                }
            }
        }

        return $arrayOk;
    }
}
