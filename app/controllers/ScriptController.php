<?php

namespace Fantamanajer\Controllers;

use \Fantamanajer\Models as Models;

class ScriptController extends ApplicationController {

    public function weeklyScript() {
        $transportObj = \Swift_SmtpTransport::newInstance()->setUsername(MAILUSERNAME)->setPassword(MAILPASSWORD);
        if (LOCAL)
            $transportObj = \Swift_MailTransport::newInstance();
        $mailerObj = \Swift_Mailer::newInstance($transportObj);

        $giornata = $this->currentGiornata->id - 1;

        $this->logger->start("WEEKLY SCRIPT");
        $punteggiExist = Models\Punteggio::getByField('idGiornata', $giornata) != NULL;
        //CONTROLLO SE È IL SECONDO GIORNO DOPO LA FINE DELLE PARTITE QUINDI ESEGUO LO SCRIPT
        if ((Models\Giornata::isWeeklyScriptDay($this->currentGiornata->id) && !$punteggiExist) || $_SESSION['roles'] == '2') {
            //$backup = fileSystem::contenutoCurl(FULLURLAUTH . Links::getLink('backup'));
            //if (!empty($backup)) {
            $this->logger->info("Starting decript file day " . $giornata);
            $path = \Fantamanajer\Lib\Decrypt::decryptFile($giornata);
            //RECUPERO I VOTI DAL SITO DELLA GAZZETTA E LI INSERISCO NEL DB
            if ($path != FALSE || Models\Voto::checkVotiExist($giornata)) {
                if ($path != FALSE) {
                    $this->logger->info("File with point created succefully");
                    $this->logger->info("Updating table players");
                    $result = Models\Giocatore::updateTabGiocatore($path, $giornata);
                    if ($result != TRUE)
                        $logger->error($result);
                } else
                    $logger->info("Points already exists in database");
                if ($giornata > 0) {
                    if (!Models\Voto::checkVotiExist($giornata)) {
                        $this->logger->info("Importing points");
                        Models\Voto::importVoti($path, $giornata);
                    }
                    $leghe = Models\Lega::getList();
                    $mail = 0;
                    foreach ($leghe as $lega) {
                        $utenti = Models\Utente::getByField('idLega', $lega->id);
                        $this->logger->info("Calculating points for league " . $lega->id);
                        try {
                            \Lib\Database\ConnectionFactory::getFactory()->getConnection()->beginTransaction();
                            foreach ($utenti as $key => $utente) {
                                $this->logger->info("Elaborating team " . $utente->id);
                                Models\Punteggio::calcolaPunti($utente, $giornata);
                            }
                            \Lib\Database\ConnectionFactory::getFactory()->getConnection()->commit();
                        } catch (PDOException $e) {
                            \Lib\Database\ConnectionFactory::getFactory()->getConnection()->rollback();
                            throw $e;
                        }
                        //ESTRAGGO LA CLASSIFICA E QUELLA DELLA GIORNATA PRECEDENTE
                        /*
                          $sum = array();
                          $classifica = Punteggio::getAllPunteggiByGiornata($giornata, $lega->id);
                          foreach ($classifica as $key => $val)
                          $sum[$key] = array_sum($val);
                          foreach ($utenti as $key => $squadra) {
                          if (!empty($squadra->email) && $squadra->isMailAbilitata()) {
                          $mailContent = new MySavant3(array('template_path' => MAILTPLDIR));
                          //$mailContent->assign('linksObj',Links);
                          $mailContent->assign('classifica', $sum);
                          $mailContent->assign('squadre', $utenti);
                          $mailContent->assign('giornata', $giornata);
                          $penalità = Punteggio::getPenalitàBySquadraAndGiornata($squadra->getId(), $giornata);
                          if ($penalità != FALSE)
                          $mailContent->assign('penalità', $penalità);
                          $mailContent->assign('utente', $squadra);
                          $punteggio = Punteggio::getByUtenteAndGiornata($squadra, $giornata);
                          $mailContent->assign('somma', $punteggio);
                          $mailContent->assign('formazione', Giocatore::getVotiGiocatoriByGiornataAndSquadra($giornata, $squadra->getId()));

                          $logger->info("Sendig mail to: " . $squadra->getEmail());
                          //MANDO LA MAIL
                          $object = "Giornata: " . $giornata . " - Punteggio: " . $punteggio;
                          $mailContent->setFilters(array("Savant3_Filter_trimwhitespace", "filter"));
                          $mailMessageObj = Swift_Message::newInstance();
                          $mailMessageObj->setSubject($object);
                          $mailMessageObj->setFrom(array(MAILFROM => "FantaManajer"));
                          $mailMessageObj->setTo(array($squadra->getEmail() => $squadra->getNome() . " " . $squadra->getCognome()));
                          $fetchMail = $mailContent->fetch('mailWeekly.tpl.php');
                          $mailMessageObj->setBody($fetchMail, 'text/html');
                          /* if (!$mailerObj->send($mailMessageObj)) {
                          $mail++;
                          $logger->warning("Error in sending mail to: " . $squadra->getEmail());
                          }else
                          $logger->info("Mail send succesfully to: " . $squadra->getEmail());
                          }
                          } */
                    }
                    if ($mail == 0)
                        $this->setFlash(self::FLASH_SUCCESS, "Operazione effettuata correttamente");
                    else
                        $this->setFlash(self::FLASH_ERROR, "Errori nell'invio delle mail");
                }
            } else {
                $this->setFlash(self::FLASH_ERROR, "Problema nel recupero dei voti dalla gazzetta");
                $this->logger->error("I can't retrieve data from gazzetta");
            }
            /* } else {
              $message->warning("Non riesco a creare il backup");
              $logger->warning("Error while creating backup");
              } */
        } else {
            $this->setFlash(self::FLASH_NOTICE, "Non puoi effettuare l'operazione ora");
            $this->logger->warning("Is not time to run it");
        }
        $this->logger->end("WEEKLY SCRIPT");
    }

    public function doTransfert() {
        $this->logger->start("ACQUISTA GIOCATORI");
        if (Models\Giornata::checkDay(($data = date("Y-m-d")), 'dataFine') || $_SESSION['usertype'] == 'superadmin') {
            $this->logger->info("Starting do transfer");
            if (Models\Selezione::doTransfertBySelezione()) {
                $this->setFlash(self::FLASH_SUCCESS, "Operazione effettuata correttamente");
                $this->logger->info("Trasnfert finished successfully");
            } else {
                $this->setFlash(self::FLASH_ERROR, "Errore nell'eseguire i trasferimenti");
                $this->logger->error("Error while doing transfer");
            }
        } else {
            $this->setFlash(self::FLASH_NOTICE, "Non puoi effettuare l'operazione ora");
            $this->logger->warning("Is not time to run it");
        }

        $this->logger->end("ACQUISTA GIOCATORI");
    }

    public function minify() {
        $jsContent = "var LOCAL = " . ((LOCAL) ? 'true' : 'false') . ";";
        $jsContent .= "var FULLURL = '" . FULLURL . "';";
        $jsContent .= "var JSURL = '" . JSURL . "';";
        $jsContent .= "var IMGSURL = '" . IMGSURL . "';";
        foreach ($this->generalJs as $val) {
            $client = new \Guzzle\Http\Client($val);
            $response = $client->createRequest()->send();
            if ($response->isSuccessful()) {
                $jsContent .= $response->getBody(true);
            }
        }

        if (!LOCAL)
            $jsContent .= file_get_contents(JAVASCRIPTSDIR . 'googleAnalytics/googleAnalytics.js');

        file_put_contents(JAVASCRIPTSDIR . 'combined/combined.js', \JSMin::minify($jsContent));
        foreach ($this->pages->pages as $key => $page) {
            $jsContent = "";
            if (isset($page->js) && !empty($page->js)) {
                foreach ($page->js as $directory => $file) {
                    if (is_array($file)) {
                        foreach ($file as $val)
                            $jsContent .= file_get_contents(JAVASCRIPTSDIR . $directory . '/' . $val . '.js');
                    } else
                        $jsContent .= file_get_contents(JAVASCRIPTSDIR . $directory . '/' . $file . '.js');
                }
            }
            if (file_exists(JAVASCRIPTSDIR . 'pages/' . $key . '.js'))
                $jsContent .= file_get_contents(JAVASCRIPTSDIR . 'pages/' . $key . '.js');
            if (!empty($jsContent))
                file_put_contents(JAVASCRIPTSDIR . 'combined/' . $key . '.js', \JSMin::minify($jsContent));
        }
        $cssContent = "";
        foreach ($this->generalCss as $key => $val) {
 //           $css_fname = strpos($val, "/") ? substr($val, strpos($val, "/") + 1) : $val;

            /*$less_fname = LESSDIR . $val . ".less";
            $css_fname = STYLESHEETSDIR . $file . ".css";
            $cache_fname = CACHEDIR . $file . ".cache";
            $cache = (file_exists($cache_fname)) ? unserialize(file_get_contents($cache_fname)) : $less_fname;
            $new_cache = \lessc::cexecute($cache);
            if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
                file_put_contents($cache_fname, serialize($new_cache));
                file_put_contents($css_fname, $new_cache['compiled']);
            }
            \lessc::ccompile($less_fname, $css_fname);*/
            $cssContent .= file_get_contents(STYLESHEETSDIR . $val);
            
        }
        file_put_contents(STYLESHEETSDIR . 'combined.css', \CssMin::minify($cssContent));
    }

    function fixPlayerPhoto() {
        $giocatori = Models\Giocatore::getList();
        foreach($giocatori as $giocatore) {
            $q = "select * from giocatore_old where cognome = :cognome and nome = :nome";
            $exe = \Lib\Database\ConnectionFactory::getFactory()->getConnection()->prepare($q);
            $exe->bindValue(":cognome", $giocatore->getCognome(), \PDO::PARAM_STR);
            $exe->bindValue(":nome", $giocatore->getNome(), \PDO::PARAM_STR);
            $exe->execute();
            
            \FirePHP::getInstance()->log($q);
            $obj = $exe->fetchObject("Fantamanajer\Models\Giocatore");
            if($obj) {
                if(file_exists($file = PLAYERSDIR . $obj->id . '.jpg')) {
                    copy($file, PLAYERSDIR . "new2/" . $giocatore->id . '.jpg');
                }
            }
            
                
        }
    }
}

