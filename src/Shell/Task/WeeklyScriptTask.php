<?php
namespace App\Shell\Task;

use Cake\Console\ConsoleOutput;
use Cake\Console\Shell;
use Cake\Http\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property GazzettaTask $Gazzetta
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class WeeklyScriptTask extends Shell
{
    public $tasks = [
        'Gazzetta',
    ];
    /**
     *
     * @var \App\Model\Entity\Matchday
     */
    private $currentMatchday = null;
    
    /**
     *
     * @var \App\Model\Entity\Season
     */
    private $currentSeason = null;
    
    public function startup() {
        parent::startup();
        $this->currentMatchday = $this->Matchdays->findCurrent();
        $this->currentSeason = $this->currentMatchday->season;
    }
    public function initialize() {
        parent::initialize();
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
    }
    
    public function main()
    {
        $this->out('Weekly script task');
    }
    
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('send_points_mails', [
            'help' => 'Send the mails of points'
        ]);
        $parser->addOption('no-send-mail',[
            'help' => 'Disable sending summary mails',
            'boolean' => true,
            'short' => 'n'
        ]);
        return $parser;
    }
    
    public function weeklyScript() {
        $matchday = $this->currentMatchday->number - 1;
        BaseController::$logger->info("start WEEKLY SCRIPT");
        $punteggiExist = Punteggio::getByField('idGiornata', $matchday) != NULL;
        //CONTROLLO SE è IL SECONDO GIORNO DOPO LA FINE DELLE PARTITE QUINDI ESEGUO LO SCRIPT
        if (((Giornata::isWeeklyScriptDay($this->currentGiornata) && !$punteggiExist) || $_SESSION['roles'] == '2') && $matchday > 0) {
            //$backup = fileSystem::contenutoCurl(FULLURLAUTH . Links::getLink('backup'));
            //if (!empty($backup)) {
            if (!Voto::checkVotiExist($matchday)) {
                BaseController::$logger->info("Starting decript file day " . $matchday);
                $path = $this->Gazzetta->getRatings($matchday);
                if ($path) {
                    BaseController::$logger->info("File with point created succefully");
                    BaseController::$logger->info("Updating table players");
                    $this->Gazzetta->updateMembers($this->currentSeason, $path);
                    BaseController::$logger->info("Importing points");
                    $this->Gazzetta->importRatings($matchday, $path);
                } 
            } else {
                BaseController::$logger->info("Points already exists in database");
            }

            if (Voto::checkVotiExist($matchday)) {
                $this->_calculatePoints($matchday);
                $this->setFlash(self::FLASH_SUCCESS, "Operazione effettuata correttamente");
            } else {
                $this->setFlash(self::FLASH_ERROR, "Problema nel recupero dei voti dalla gazzetta");
                BaseController::$logger->error("I can't retrieve data from gazzetta");
            }
            /* } else {
              $message->warning("Non riesco a creare il backup");
              $logger->warning("Error while creating backup");
              } */
        } else {
            $this->setFlash(self::FLASH_NOTICE, "Non puoi effettuare l'operazione ora");
            BaseController::$logger->warning("Is not time to run it");
        }
        BaseController::$logger->info("End WEEKLY SCRIPT");
    }

    protected function _calculatePoints($giornata) {
        $leghe = Lega::getList();
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            foreach ($leghe as $lega) {
                BaseController::$logger->info("Calculating points for league " . $lega->id);
                $utenti = Utente::getByField('idLega', $lega->id);
                foreach ($utenti as $key => $utente) {
                    BaseController::$logger->info("Elaborating team " . $key);
                    Punteggio::calcolaPunti($utente, $giornata);
                }
                if (!$this->params['no-send-mail']) {
                    $this->sendWeeklyMails($giornata, $lega);
                }
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollback();
            throw $e;
        }
    }

    public function sendPointsMails($giornata = NULL, Lega $lega = NULL) {
        $lega = is_null($lega) ? $this->currentLega : $lega;
        $giornata = is_null($giornata) ? ($this->currentGiornata->id - 1) : $giornata;
        $transport = (LOCAL) ? Swift_MailTransport::newInstance() : Swift_SmtpTransport::newInstance()->setUsername(MAILUSERNAME)->setPassword(MAILPASSWORD);
        $mailer = Swift_Mailer::newInstance($transport);
        $classifica = Punteggio::getAllPunteggiByGiornata($giornata, $lega->id);
        $sum = array();
        foreach ($classifica as $key => $val) {
            $sum[$key] = array_sum($val);
        }
        foreach ($lega->getUtenti() as $key => $squadra) {
            if (!empty($squadra->email) && $squadra->isMailAbilitata()) {
                if ($this->_sendWeeklyMail($mailer, $squadra, $giornata, $classifica)) {
                    BaseController::$logger->warning("Error in sending mail to: " . $squadra->getEmail());
                } else {
                    BaseController::$logger->info("Mail send succesfully to: " . $squadra->getEmail());
                }
            }
        }
    }

    protected function _sendPointMail(Swift_Mailer $mailer, Utente $squadra, $giornata, $classifica) {
        $punteggio = Punteggio::getByUtenteAndGiornata($squadra, $giornata);
        $mailContent = new Savant3(array('template_path' => MAILTPLDIR));
        $mailContent->assign('classifica', $classifica);
        $mailContent->assign('squadre', $squadra->getLega()->getUtenti());
        $mailContent->assign('giornata', $giornata);
        $mailContent->assign('penalità', Punteggio::getPenalitàBySquadraAndGiornata($squadra->getId(), $giornata));
        $mailContent->assign('utente', $squadra);
        $mailContent->assign('somma', $punteggio);
        $mailContent->assign('formazione', Giocatore::getVotiGiocatoriByGiornataAndSquadra($giornata, $squadra->getId()));

        BaseController::$logger->info("Sendig mail to: " . $squadra->getEmail());
        //MANDO LA MAIL
        $object = "Punteggio " . $squadra->getNomeSquadra() . " della giornata " . $giornata . ": " . $punteggio;
        $mailContent->setFilters(array("Savant3_Filter_trimwhitespace", "filter"));
        $message = Swift_Message::newInstance();
        $message->setSubject($object);
        $message->setFrom(array(MAILFROM => "FantaManajer"));
        $message->setTo(array($squadra->getEmail() => $squadra->getNome() . " " . $squadra->getCognome()));
        $message->setBody($mailContent->fetch('weekly.php'), 'text/html');
        return $mailer->send($message) > 0;
    }
}