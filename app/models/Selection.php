<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\SelectionsTable;
use Fantamanajer\Models\View\MemberStats;
use FirePHP;
use Lib\Database\ConnectionFactory;
use Lib\FormException;
use PDO;
use PDOException;

class Selection extends SelectionsTable {

    /**
     * 
     * @param Member $member
     * @param Championship $championship
     * @return Selection
     */
    public static function getByMemberAndChampionship(Member $member, Championship $championship) {
        $q = "SELECT *
		FROM " . self::TABLE_NAME . "
		WHERE new_member_id = :member_id AND team_id IN (SELECT id FROM teams WHERE championship_id = :championship_id)";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":member_id", $member->getId(), PDO::PARAM_INT);
        $exe->bindValue(":championship_id", $championship->getId(), PDO::PARAM_INT);
        return $exe->fetchObject(__CLASS__);
    }

    /**
     * @todo Sistemare selezione
     * @param int $giocNew
     * @param int $giocOld
     * @param int $idLega
     * @param int $idUtente
     */
    public static function updateGioc($giocNew, $giocOld, $idLega, $idUtente) {
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $q = "SELECT numSelezioni
				FROM selezione
				WHERE giocNew = '" . $giocNew . "' AND idLega = '" . $idLega . "' LOCK IN SHARE MODE";
            $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
            $values = $exe->fetchObject(__CLASS__);
            if ($values != FALSE) {
                $q = "UPDATE selezione
					SET giocOld = '0', giocNew = NULL, numSelezioni = '" . ($values->numSelezioni - 1) . "'
					WHERE giocNew = '" . $giocNew . "' AND idLega = '" . $idLega . "'";
                ConnectionFactory::getFactory()->getConnection()->exec($q);
            }
            $q = "SELECT numSelezioni
				FROM selezione
				WHERE giocNew IS NOT NULL AND idUtente = '" . $idUtente . "'  LOCK IN SHARE MODE";
            $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
            $values = $exe->fetchObject(__CLASS__);
            if ($values != FALSE) {
                $q = "UPDATE selezione
					SET giocOld = '" . $giocOld . "', giocNew = '" . $giocNew . "',numSelezioni = '" . ($values2->numSelezioni + 1) . "'
					WHERE idUtente = '" . $idUtente . "'";
                ConnectionFactory::getFactory()->getConnection()->exec($q);
            } else {
                $q = "INSERT INTO selezione
					VALUES ('" . $idLega . "','" . $idUtente . "','" . $giocOld . "','" . $giocNew . "','1')";
                ConnectionFactory::getFactory()->getConnection()->exec($q);
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public static function svuota() {
        $q = "TRUNCATE TABLE selezione";
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    /**
     * 
     * @param array $array
     * @return boolean
     * @throws FormException
     */
    public function check(array $array) {
        if (is_null($this->new_member_id)) {
            return TRUE;
        }
        $numTransfert = count(Transfert::getByField('team_id', $this->getTeamId()));
        if ($numTransfert >= $_SESSION['championship_data']->number_transferts) {
            throw new FormException('Hai raggiunto il limite di trasferimenti');
        }
        $memberNew = MemberStats::getById($this->getNewMemberId());
        $memberOld = MemberStats::getById($this->getOldMemberId());
        if ($memberOld->role->id != $memberNew->role->id) {
            throw new FormException('I giocatori devono avere lo stesso ruolo');
        }
        if ($this->getNumberSelections() > $_SESSION['championship_data']->number_selections) {
            throw new FormException("Hai già cambiato " . $_SESSION['championship_data']->number_selections . " volte il tuo acquisto");
        }
        $team = Team::getById($this->getTeamId());
        $selection = self::getByMemberAndChampionship($memberNew, $team->getChampionship());
        if ($selection != NULL) {
            $ranking = Score::getRankingByMatchday($team->getChampionship(), Matchday::getCurrent());
            if ($ranking[$this->getTeamId()] < $ranking[$selection->getTeamId()]) {

                $selection->setNumberSelections($selection->getNumberSelections() - 1);
                $selection->setNewMemberId(NULL);
                $selection->setOldMemberId(NULL);
                $selection->save();
                //Selezione::updateGioc($acquisto,$lasciato,$_SESSION['idLega'],$_SESSION['idUtente']);
                // $mailContent->assign('giocatore', $selezione->getGiocatoreNew()->nome . ' ' . $selezione->getGiocatoreNew()->cognome);
                //$appo = $squadre[$acquistoDett->idSquadraAcquisto];
                /**
                 * TODO: invio mail
                 */
                //Mail::sendEmail($squadre[$appo]->mail, $mailContent->fetch(MAILTPLDIR . 'mailGiocatoreRubato.tpl.php'), 'Giocatore rubato!');
            } else {
                throw new FormException('Un altra squadra inferiore di te ha già selezionato questo giocatore');
            }
        }
        return TRUE;
    }

    public static function doTransfertBySelezione() {
        $giornata = Giornata::getCurrentGiornata()->id;
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $selezioni = self::getList();
            foreach ($selezioni as $selezione) {
                FirePHP::getInstance()->log($selezione);
                if (!is_null($selezione->idGiocatoreOld) && !is_null($selezione->idGiocatoreNew)) {
                    $trasferimento = new Trasferimento();
                    $trasferimento->setIdGiocatoreOld($selezione->idGiocatoreOld);
                    $trasferimento->setIdGiocatoreNew($selezione->idGiocatoreNew);
                    $trasferimento->setIdUtente($selezione->idUtente);
                    $trasferimento->setIdGiornata($giornata);
                    $trasferimento->setObbligato(!$selezione->getGiocatoreOld()->isAttivo());
                    $trasferimento->save();
                }
            }
            self::svuota();
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public function save(array $parameters = array()) {
        $this->setNumberSelections($this->getNumberSelections() + 1);
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            FirePHP::getInstance()->log("salvo");
            parent::save($parameters);
            $event = new Event();
            $event->setCreatedAt(new \DateTime());
            $event->setType(Event::SELEZIONEGIOCATORE);
            $event->setTeamId($this->getTeamId());
            $event->save();
            ConnectionFactory::getFactory()->getConnection()->commit();
            return TRUE;
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
    }

}
