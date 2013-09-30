<?php

namespace Fantamanajer\Controllers;

use CssMin;
use Fantamanajer\Lib\Decrypt;
use Fantamanajer\Models\Formazione;
use Fantamanajer\Models\Giocatore;
use Fantamanajer\Models\Giornata;
use Fantamanajer\Models\Lega;
use Fantamanajer\Models\Punteggio;
use Fantamanajer\Models\Selezione;
use Fantamanajer\Models\Utente;
use Fantamanajer\Models\View\GiocatoreStatistiche;
use Fantamanajer\Models\Voto;
use FirePHP;
use Guzzle\Http\Client;
use JSMin;
use Lib\Database\ConnectionFactory;
use PDO;
use PDOException;
use Savant3;
use Swift_Mailer;
use Swift_MailTransport;
use Swift_Message;
use Swift_SmtpTransport;

class ScriptController extends ApplicationController {

    public function weeklyScript() {
        $giornata = $this->currentGiornata->id - 1;

        $this->logger->start("WEEKLY SCRIPT");
        $punteggiExist = Punteggio::getByField('idGiornata', $giornata) != NULL;
        //CONTROLLO SE Ãˆ IL SECONDO GIORNO DOPO LA FINE DELLE PARTITE QUINDI ESEGUO LO SCRIPT
        if (((Giornata::isWeeklyScriptDay($this->currentGiornata->id) && !$punteggiExist) || $_SESSION['roles'] == '2') && $giornata > 0) {
            //$backup = fileSystem::contenutoCurl(FULLURLAUTH . Links::getLink('backup'));
            //if (!empty($backup)) {
            if (!Voto::checkVotiExist($giornata)) {
                $this->logger->info("Starting decript file day " . $giornata);
                $path = Decrypt::decryptFile($giornata);
                if ($path != FALSE) {
                    $this->logger->info("File with point created succefully");
                    $this->logger->info("Updating table players");
                    Giocatore::updateTabGiocatore($path, $giornata);
                    $this->logger->info("Importing points");
                    Voto::importVoti($path, $giornata);
                } 
            } else {
                $this->logger->info("Points already exists in database");
            }

            if (Voto::checkVotiExist($giornata)) {
                $this->_calculatePoints($giornata);
                $this->setFlash(self::FLASH_SUCCESS, "Operazione effettuata correttamente");
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

    protected function _calculatePoints($giornata) {
        $leghe = Lega::getList();
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            foreach ($leghe as $lega) {
                $this->logger->info("Calculating points for league " . $lega->id);
                $utenti = Utente::getByField('idLega', $lega->id);
                foreach ($utenti as $key => $utente) {
                    $this->logger->info("Elaborating team " . $key);
                    Punteggio::calcolaPunti($utente, $giornata);
                }
                $this->sendWeeklyMails($giornata, $lega);
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollback();
            throw $e;
        }
    }

    public function sendWeeklyMails($giornata, Lega $lega) {
        $mailer = Swift_Mailer::newInstance((LOCAL) ? Swift_MailTransport::newInstance() : Swift_SmtpTransport::newInstance()->setUsername(MAILUSERNAME)->setPassword(MAILPASSWORD));
        $classifica = Punteggio::getAllPunteggiByGiornata($giornata, $lega->id);
        $sum = array();
        foreach ($classifica as $key => $val) {
            $sum[$key] = array_sum($val);
        }
        foreach ($lega->getUtenti() as $key => $squadra) {
            if (!empty($squadra->email) && $squadra->isMailAbilitata()) {
                if ($this->_sendWeeklyMail($mailer, $squadra, $giornata, $classifica)) {
                    $this->logger->warning("Error in sending mail to: " . $squadra->getEmail());
                } else {
                    $this->logger->info("Mail send succesfully to: " . $squadra->getEmail());
                }
            }
        }
    }

    protected function _sendWeeklyMail(Swift_Mailer $mailer, Utente $squadra, $giornata, $classifica) {
        $punteggio = Punteggio::getByUtenteAndGiornata($squadra, $giornata);
        $mailContent = new Savant3(array('template_path' => MAILTPLDIR));
        $mailContent->assign('classifica', $classifica);
        $mailContent->assign('squadre', $squadra->getLega()->getUtenti());
        $mailContent->assign('giornata', $giornata);
        $penalitÃ  = Punteggio::getPenalitÃ BySquadraAndGiornata($squadra->getId(), $giornata);
        if ($penalitÃ  != FALSE) {
            $mailContent->assign('penalitÃ ', $penalitÃ );
        }
        $mailContent->assign('utente', $squadra);
        $mailContent->assign('somma', $punteggio);
        $mailContent->assign('formazione', Giocatore::getVotiGiocatoriByGiornataAndSquadra($giornata, $squadra->getId()));

        $this->logger->info("Sendig mail to: " . $squadra->getEmail());
        //MANDO LA MAIL
        $object = "Punteggio " . $squadra . " della giornata" . $giornata . ": " . $punteggio;
        $mailContent->setFilters(array("Savant3_Filter_trimwhitespace", "filter"));
        $message = Swift_Message::newInstance();
        $message->setSubject($object);
        $message->setFrom(array(MAILFROM => "FantaManajer"));
        $message->setTo(array($squadra->getEmail() => $squadra->getNome() . " " . $squadra->getCognome()));
        $message->setBody($mailContent->fetch('mailWeekly.php'), 'text/html');
        return $mailer->send();
    }

    public function doTransfert() {
        $this->logger->start("ACQUISTA GIOCATORI");
        if (Giornata::isDoTransertDay() || $_SESSION['usertype'] == 'superadmin') {
            $this->logger->info("Starting do transfer");
            if (Selezione::doTransfertBySelezione()) {
                $this->setFlash(self::FLASH_SUCCESS, "Operazione effettuata correttamente");
                $this->logger->info("Transfert finished successfully");
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
            $client = new Client($val);
            $response = $client->createRequest()->send();
            if ($response->isSuccessful()) {
                $jsContent .= $response->getBody(true);
            }
        }

        if (!LOCAL)
            $jsContent .= file_get_contents(JAVASCRIPTSDIR . 'googleAnalytics/googleAnalytics.js');

        file_put_contents(JAVASCRIPTSDIR . 'combined/combined.js', JSMin::minify($jsContent));
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
                file_put_contents(JAVASCRIPTSDIR . 'combined/' . $key . '.js', JSMin::minify($jsContent));
        }
        $cssContent = "";
        foreach ($this->generalCss as $key => $val) {
            //           $css_fname = strpos($val, "/") ? substr($val, strpos($val, "/") + 1) : $val;

            /* $less_fname = LESSDIR . $val . ".less";
              $css_fname = STYLESHEETSDIR . $file . ".css";
              $cache_fname = CACHEDIR . $file . ".cache";
              $cache = (file_exists($cache_fname)) ? unserialize(file_get_contents($cache_fname)) : $less_fname;
              $new_cache = \lessc::cexecute($cache);
              if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
              file_put_contents($cache_fname, serialize($new_cache));
              file_put_contents($css_fname, $new_cache['compiled']);
              }
              \lessc::ccompile($less_fname, $css_fname); */
            $cssContent .= file_get_contents(STYLESHEETSDIR . $val);
        }
        file_put_contents(STYLESHEETSDIR . 'combined.css', CssMin::minify($cssContent));
    }

    function fixPlayerPhoto() {
        $giocatori = Giocatore::getList();
        foreach ($giocatori as $giocatore) {
            $q = "select * from giocatore_old where cognome = :cognome and nome = :nome";
            $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
            $exe->bindValue(":cognome", $giocatore->getCognome(), PDO::PARAM_STR);
            $exe->bindValue(":nome", $giocatore->getNome(), PDO::PARAM_STR);
            $exe->execute();

            FirePHP::getInstance()->log($q);
            $obj = $exe->fetchObject("Fantamanajer\Models\Giocatore");
            if ($obj) {
                if (file_exists($file = PLAYERSDIR . $obj->id . '.jpg')) {
                    copy($file, PLAYERSDIR . "new2/" . $giocatore->id . '.jpg');
                }
            }
        }
    }

    function sendMails() {
        if (LOCAL)
            return false;

        $transportObj = Swift_SmtpTransport::newInstance()->setUsername(MAILUSERNAME)->setPassword(MAILPASSWORD);
        $mailerObj = Swift_Mailer::newInstance($transportObj);

        //$logger->start("MAIL FORMAZIONE");
        if (Giornata::isSendMailDay() || $_SESSION['usertype'] == 'superadmin') {
            $leghe = Lega::getList();
            $mail = 0;
            foreach ($leghe as $lega) {
                $squadre = Utente::getByField('idLega', $lega->id);
                $formazioni = array();
                foreach ($squadre as $key => $squadra) {
                    $formazione = Formazione::getFormazioneBySquadraAndGiornata($key, GIORNATA);

                    if ($formazione != FALSE) {
                        $giocatori[$key] = GiocatoreStatistiche::getByField('idUtente', $key);
                        $formazioni[$key] = $formazione;
                    }
                }

                $mailContent = new Savant3(array('template_path' => MAILTPLDIR));
                $mailContent->assign('squadre', $squadre);
                $mailContent->assign('formazione', $formazioni);
                $mailContent->assign('giocatori', $giocatori);
//$mailContent->assign('cap',$cap);
                $mailContent->assign('giornata', GIORNATA);

                $object = "Formazioni giornata: " . GIORNATA;
//$mailContent->setFilters(array("Savant3_Filter_trimwhitespace","filter"));
                $mailMessageObj = Swift_Message::newInstance();
                $mailMessageObj->setSubject($object);
                $mailMessageObj->setFrom(array(MAILFROM => "FantaManajer"));
                $fetchMail = $mailContent->fetch('mailFormazioni.php');

                $mailMessageObj->setBody($fetchMail, 'text/html');
                foreach ($squadre as $key => $squadra) {
                    if (!is_null($squadra->getEmail()) && $squadra->isMailAbilitata) {
                        $this->logger->info("Sending mail to: " . $squadra->getEmail());
                        $mailMessageObj->setTo(array($squadra->getEmail() => $squadra->getNome() . " " . $squadra->getCognome()));
                        if (!$mailerObj->send($mailMessageObj)) {
                            $mail++;
                            $this->logger->warning("Error in sending mail to: " . $squadra->getEmail());
                        } else
                            $this->logger->info("Mail send succesfully to: " . $squadra->getEmail());
                    }
                }
            }
            if ($mail == 0)
                $this->setFlash(self::FLASH_SUCCESS, "Operazione effettuata correttamente");
            else
                $this->setFlash(self::FLASH_SUCCESS, "Errori nell'invio delle mail");
        } else {
            $this->setFlash(self::FLASH_SUCCESS, "Non puoi effettuare l'operazione ora");
            $this->logger->warning("Is not time to run it");
        }
        $this->logger->end("MAIL FORMAZIONE");
        
    }

}
