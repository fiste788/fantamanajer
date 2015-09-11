<?php

namespace Fantamanajer\Lib;

use FirePHP;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class FileSystem {

    public static function getDirIntoFolder($folder) {
        $output = array();
        if ($handle = opendir($folder)) {
            while (FALSE !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != ".svn" && is_dir($folder . '/' . $file))
                    $output[] = $file;
            }
            closedir($handle);
            return $output;
        } else {
            return "La cartella " . $folder . " non esiste";
            die;
        }
    }

    public static function getFileIntoFolder($folder) {
        $output = array();
        if ($handle = opendir($folder)) {
            while (FALSE !== ($file = readdir($handle))) {
                if ($file != ".htaccess" && $file != "." && $file != ".." && $file != ".svn")
                    $output[] = $file;
            }
            closedir($handle);
            return $output;
        } else {
            return "La cartella " . $folder . " non esiste";
            die;
        }
    }

    public static function getFileIntoFolderRecursively($directory, $recursive) {
        $array_items = array();
        if ($handle = opendir($directory)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != '.svn') {
                    if (is_dir($directory . "/" . $file)) {
                        if ($recursive)
                            $array_items = array_merge($array_items, self::getFileIntoFolderRecursively($directory . "/" . $file, $recursive));
                        $file = $directory . "/" . $file;
                        $array_items[] = preg_replace("/\/\//si", "/", $file);
                    } else {
                        $file = $directory . "/" . $file;
                        $array_items[] = preg_replace("/\/\//si", "/", $file);
                    }
                }
            }
            closedir($handle);
        }
        return $array_items;
    }

    public static function returnArray($path, $sep = ";", $riga_intest = FALSE) {
        if (!file_exists($path))
            die("File non esistente");
        $content = trim(file_get_contents($path));
        $players = explode("\n", $content);
        if ($riga_intest)  //se esiste tolgo la riga descrittiva d'intestazione
            array_shift($riga_intest);
        foreach ($players as $val) {
            $par = explode($sep, $val);
            $players = trim($val);
            $playersOk[$par[0]] = $par;
        }
        return $playersOk;
    }

    public static function contenutoCurl($url, $user = NULL, $pass = NULL) {
        $handler = curl_init();

        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_HEADER, FALSE);
        curl_setopt($handler, CURLOPT_COOKIESESSION, TRUE);
        if (!is_null($user)) {
            curl_setopt($handler, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($handler, CURLOPT_USERPWD, $user . ":" . $pass);
            curl_setopt($handler, CURLOPT_BINARYTRANSFER, TRUE);
        }
        ob_start();
        curl_exec($handler);
        curl_close($handler);
        $string = ob_get_contents();
        ob_end_clean();
        return $string;
    }    
    
    public static function getUrlContent($site) {
        $client = new Client();
        $response = $client->get($site);
        if ($response->getStatusCode() == 200) {
            return $response->getBody();
        }
    }

    public static function scaricaLista($percorso) {
        if (file_exists($percorso))
            unlink($percorso);
        $handle = fopen($percorso, "a");
        $array = array("P" => "portieri", "D" => "difensori", "C" => "centrocampisti", "A" => "attaccanti");
        foreach ($array as $keyruolo => $ruolo) {
            $link = "http://www.fantagazzetta.com/quotazioni_" . $ruolo . "_gazzetta_dello_sport.asp";
            $contenuto = self::contenutoCurl($link);
            $contenuto = preg_replace("/\n/", "", $contenuto);
            preg_match("/<table.*?class=\"statistiche\">\s*(.*?<\/table>)/", $contenuto, $matches);
            $keywords = explode("<tr", $matches[0]);
            array_shift($keywords);
            array_shift($keywords);
            foreach ($keywords as $key) {
                $espre = "/(\s*\/?<[^<>]+>)+/";
                $key = preg_replace($espre, "\t", $key);
                $pieces = explode("\t", $key);
                foreach ($pieces as $key => $val)
                    $pieces[$key] = trim($val);
                $pieces = array_map("htmlspecialchars", $pieces);
                $pieces[6] = substr($pieces[6], 0, 3);
                $pieces[2] = ucwords(strtolower($pieces[2]));
                fwrite($handle, "$pieces[1];$pieces[2];$keyruolo;$pieces[6]\n");
            }
        }
        fclose($handle);
    }

    public static function getLastBackup() {
        $nomeBackup = @file_get_contents("http://www.fantamanajer.it/docs/nomeBackup.txt");
        /* if(!empty($nomeBackup)) {
          $content = self::contenutoCurl('http://www.fantamanajer.it/db/' . $nomeBackup . '.sql.gz',"administrator","banana");
          FirePHP::getInstance()->log($content);
          if(!empty($content) && !is_null($content))
          return implode(gzfile($content));
          }
          return FALSE; */
        if (!empty($nomeBackup) && file('http://administrator:banana@www.fantamanajer.it/db/' . $nomeBackup . '.sql.gz') != FALSE)
            return implode(gzfile('http://administrator:banana@www.fantamanajer.it/db/' . $nomeBackup . '.sql.gz'));
        else
            return FALSE;
    }


    

    public static function deleteFiles($dir,$ext,$days) {
        if ($handle = opendir($dir)) {
            while (false !== ($filename = readdir($handle))) {
                if ($filename != '.' && $filename != '..' && end(explode(".",$filename)) == $ext && filemtime($dir . $filename) < strtotime("-$days days"))
                    unlink($dir . $filename);
            }
            closedir($handle);
        }
    }

}
