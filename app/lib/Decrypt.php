<?php

namespace Fantamanajer\Lib;

class Decrypt {

    /**
     * campi file .mxm
      0	cod
      1	bo?
      2	nomecognome
      3	club
      4	1=attivo;0=passivo
      5	ruolo(0=PORTIERE,1=DIFENSORE,2=CENTROCAMPISTA,3=ATTACCANTE)
      6	1=valutato;0=non valutato
      7	punti
      8	bo
      9	1=valutato,0=senzavoto
      10	voto
      11	gol
      12	gol subiti
      13  gol vittoria
      14  gol pareggio
      15	assist
      16	ammonizione
      17	espulsione
      18  rigori calciati
      19  rigori subiti
      20  boh
      23	presenza
      24	titolare
      25  votopolitico portiere
      27	costo

     */
    // per calcolare la chiave di decrypt...da lanciare manualmente
    public static function calculateKey() {
        $pathcript = DOCSDIR . "mcc01.rcs"; //file criptato .rcs
        $pathencript = DOCSDIR . "mcc01.txt"; //file decritato es prima riga 101|0|"ABBIATI Christian"|"MILAN"|1|0|0|0.0|0|0|0.0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|16
        $cript = file_get_contents($pathcript);
        $encript = file_get_contents($pathencript);
        $ris = "";
        for ($i = 0; $i < 28; $i++) {
            $xor1 = hexdec(bin2hex($cript[$i]));
            $xor2 = hexdec(bin2hex($encript[$i]));
            if ($i != 0)
                $ris .= '-';
            $ris .= dechex($xor1 ^ $xor2);
        }

        FirePHP::getInstance()->log($ris);
        return $ris;
    }

    public static function decryptFile($giornata, $scostamentoGazzetta = 0) {
        $percorsoCsv = VOTICSVDIR . "Giornata" . str_pad($giornata, 2, "0", STR_PAD_LEFT) . ".csv";
        $percorsoXml = VOTIXMLDIR . "Giornata" . str_pad($giornata, 2, "0", STR_PAD_LEFT) . ".xml";
        $percorsoContent = (file_exists($percorsoCsv)) ? trim(file_get_contents($percorsoCsv)) : "";
        $giornataGazzetta = ($giornata + $scostamentoGazzetta);
        if (!empty($percorsoContent) && ($giornata != 0)) {
            return $percorsoCsv;
        } else {
            $url = self::getFileUrl($giornataGazzetta);
            if (!empty($url)) {
                $content = self::decryptMXMFile($url);
                if (!empty($content)) {
                    self::writeCsvVoti($content, $percorsoCsv);
                    self::writeXmlVoti($content, $percorsoXml);
                    return $percorsoCsv;
                }
            }
        }
    }

    protected static function getFileUrl($giornata) {
        \FirePHP::getInstance()->log("Scarico voti giornata " . $giornata);
        $content = self::getUrlContent("http://maxigames.maxisoft.it/downloads.php");
        if ($content != "") {
            $crawler = new \Symfony\Component\DomCrawler\Crawler();
            $crawler->addContent($content);
            $td = $crawler->filter("#content td:contains('Giornata $giornata')");
            if($td->count() > 0) {
                $url = $td->nextAll()->filter("a")->attr("href");
                $content = self::getUrlContent($url);
                if ($content != "") {
                    $crawler = new \Symfony\Component\DomCrawler\Crawler();
                    $crawler->addContent($content);
                    $url = $crawler->filter("#default_content_download_button")->attr("href");
                    return $url;
                }
            }
        }
    }

    protected static function decryptMXMFile($url) {
        $stringa = "";
        if ($p_file = fopen($url, "r")) {
            $decrypt = "37;34;37;21;6a;36;35;67;72;34;4A;49;4F;50;35;35;E8;2B;66;6A;75;79;72;24;25;65;72;32";
            $explode_xor = explode(";", $decrypt);
            $i = 0;
            $votiContent = file_get_contents($url);
            if (!empty($votiContent)) {
                while (!feof($p_file)) {
                    if ($i == count($explode_xor)) {
                        $i = 0;
                    }
                    $linea = fgets($p_file, 2);
                    $xor2 = hexdec(bin2hex($linea)) ^ hexdec($explode_xor[$i]);
                    $i++;
                    $stringa .= chr($xor2);
                }
            }
            fclose($p_file);
        }
        return $stringa;
    }

    public static function getUrlContent($site) {
        $client = new \Guzzle\Http\Client($site);
        $response = $client->createRequest()->send();
        if ($response->isSuccessful()) {
            return $response->getBody(true);
        }
    }

    protected static function writeCsvVoti($content, $percorso) {
        if ($scriviFile = fopen($percorso, "w")) {
            $pezzi = explode("\n", $content);
            array_pop($pezzi);
            foreach ($pezzi as $key => $val) {
                $pieces = explode("|", $val);
                $pezzi[$key] = join(";", $pieces);
                if ($pieces[4] == 0) {
                    unset($pezzi[$key]);
                }
            }
            fwrite($scriviFile, join("\n", $pezzi));
            fclose($scriviFile);
        }
    }

    public static function writeXmlVoti($content, $percorso) {
        $tree = explode("\n", $content);
        $xml = new \XMLWriter();
        $ruoli = array("P", "D", "C", "A");
        $xml->openURI($percorso);
        $xml->startDocument("1.0");
        $xml->startElement("players");
        foreach ($tree as $row) {
            if(!empty($row)) {
                $node = explode("|", $row);
                $xml->startElement("player");
                $xml->writeElement("id", $node[0]);
                $xml->writeElement("nome", trim($node[2], '"'));
                $xml->writeElement("club", substr(trim($node[3], '"'), 0, 3));
                $xml->writeElement("ruolo", $ruoli[$node[5]]);
                $xml->writeElement("valutato", $node[6]); //1=valutato,0=senzavoto
                $xml->writeElement("punti", $node[7]);
                $xml->writeElement("voto", $node[10]);
                $xml->writeElement("gol", $node[11]);
                $xml->writeElement("golSubiti", $node[12]);
                $xml->writeElement("golVittoria", $node[13]);
                $xml->writeElement("golPareggio", $node[14]);
                $xml->writeElement("assist", $node[15]);
                $xml->writeElement("ammonito", $node[16]);
                $xml->writeElement("espulso", $node[17]);
                $xml->writeElement("rigoriSegnati", $node[18]);
                $xml->writeElement("rigoriSubiti", $node[19]);
                $xml->writeElement("presenza", $node[23]);
                $xml->writeElement("titolare", $node[24]);
                $xml->writeElement("quotazione", trim($node[27]));
                $xml->endElement();
            }
        }
        $xml->endDocument();
    }

}