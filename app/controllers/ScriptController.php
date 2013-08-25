<?php

namespace Fantamanajer\Controllers;

use \Fantamanajer\Models as Models;

class ScriptController extends ApplicationController {

    public function weeklyScript() {
        $transportObj = Mail::getDefaultTransport();
        $mailerObj = Swift_Mailer::newInstance($transportObj);

        $giornata = GIORNATA - 1;

        $logger->start("WEEKLY SCRIPT");
        $punteggiExist = Punteggio::getByField('idGiornata', $giornata) != NULL;
        //CONTROLLO SE È IL SECONDO GIORNO DOPO LA FINE DELLE PARTITE QUINDI ESEGUO LO SCRIPT
        if ((Giornata::isWeeklyScriptDay() && !$punteggiExist) || $_SESSION['roles'] == '2') {
            $backup = fileSystem::contenutoCurl(FULLURLAUTH . Links::getLink('backup'));
            if (!empty($backup)) {
                $logger->info("Starting decript file day " . $giornata);
                $path = Decrypt::decryptCdfile($giornata, 0);
                //RECUPERO I VOTI DAL SITO DELLA GAZZETTA E LI INSERISCO NEL DB
                if ($path != FALSE || Voto::checkVotiExist($giornata)) {
                    if ($path != FALSE) {
                        $logger->info("File with point created succefully");
                        $logger->info("Updating table players");
                        $result = Giocatore::updateTabGiocatore($path, $giornata);
                        if ($result != TRUE)
                            $logger->error($result);
                    } else
                        $logger->info("Points already exists in database");
                    if ($giornata > 0) {
                        if (!Voto::checkVotiExist($giornata)) {
                            $logger->info("Importing points");
                            Voto::importVoti($path, $giornata);
                        }
                        $leghe = Lega::getList();
                        $mail = 0;
                        foreach ($leghe as $lega) {
                            $utenti = Utente::getByField('idLega', $lega->id);
                            $logger->info("Calculating points for league " . $lega->id);
                            try {
                                ConnectionFactory::getFactory()->getConnection()->beginTransaction();
                                $sum = array();
                                foreach ($utenti as $key => $utente) {
                                    $logger->info("Elaborating team " . $utente->id);
                                    Punteggio::calcolaPunti($utente, $giornata);
                                }
                                ConnectionFactory::getFactory()->getConnection()->commit();
                            } catch (PDOException $e) {
                                ConnectionFactory::getFactory()->getConnection()->rollback();
                                throw $e;
                            }
                            //ESTRAGGO LA CLASSIFICA E QUELLA DELLA GIORNATA PRECEDENTE
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
                                      $logger->info("Mail send succesfully to: " . $squadra->getEmail()); */
                                }
                            }
                        }
                    }
                    if ($mail == 0)
                        $message->success("Operazione effettuata correttamente");
                    else
                        $message->error("Errori nell'invio delle mail");
                } else {
                    $message->error("Problema nel recupero dei voti dalla gazzetta");
                    $logger->error("I can't retrieve data from gazzetta");
                }
            } else {
                $message->warning("Non riesco a creare il backup");
                $logger->warning("Error while creating backup");
            }
        } else {
            $message->warning("Non puoi effettuare l'operazione ora");
            $logger->warning("Is not time to run it");
        }
        $logger->end("WEEKLY SCRIPT");
        $contentTpl->assign('message', $message);
    }

    public function doTransfert() {
        $this->logger->start("ACQUISTA GIOCATORI");
        if (Models\Giornata::checkDay(($data = date("Y-m-d")), 'dataFine') || $_SESSION['usertype'] == 'superadmin') {
            $this->logger->info("Starting do transfer");
            if (Models\Selezione::doTransfertBySelezione()) {
                $this->setFlash(self::FLASH_SUCCESS, "Operazione effettuata correttamente");
                $this->logger->info("Trasnfert finished successfully");
            } else {
                $this->setFlash(self::FLASH_ERROR,"Errore nell'eseguire i trasferimenti");
                $this->logger->error("Error while doing transfer");
            }
        } else {
            $this->setFlash(self::FLASH_NOTICE,"Non puoi effettuare l'operazione ora");
            $this->logger->warning("Is not time to run it");
        }

        $this->logger->end("ACQUISTA GIOCATORI");
    }

}

