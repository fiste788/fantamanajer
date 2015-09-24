<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Giocatore;
use Fantamanajer\Models\Giornata;
use Fantamanajer\Models\Trasferimento;
use Fantamanajer\Models\Utente;
use Lib\Database\Table;

abstract class TransfertsTable extends Table {

    const TABLE_NAME = "transferts";

    /**
     *
     * @var int
     */
    public $old_member_id;

    /**
     *
     * @var int
     */
    public $new_member_id;

    /**
     *
     * @var int
     */
    public $team_id;

    /**
     *
     * @var int
     */
    public $matchday_id;

    /**
     *
     * @var boolean
     */
    public $constrained;

    public function __construct() {
        parent::__construct();
        $this->old_member_id = is_null($this->old_member_id) ? NULL : $this->getOldMemberId();
        $this->new_member_id = is_null($this->new_member_id) ? NULL : $this->getNewMemberID();
        $this->team_id = is_null($this->team_id) ? NULL : $this->getTeamId();
        $this->matchday_id = is_null($this->matchday_id) ? NULL : $this->getMatchdayId();
        $this->constrained = is_null($this->constrained) ? NULL : $this->isConstrained();
    }

    /**
     * 
     * @return int
     */
    public function getOldMemberId() {
        return (int) $this->old_member_id;
    }

    /**
     * 
     * @return int
     */
    public function getNewMemberId() {
        return (int) $this->new_member_id;
    }

    /**
     * 
     * @return int
     */
    public function getTeamId() {
        return (int) $this->team_id;
    }

    /**
     * 
     * @return int
     */
    public function getMatchdayId() {
        return (int) $this->matchday_id;
    }

    /**
     * 
     * @return boolean
     */
    public function isConstrained() {
        return (boolean) $this->constrained;
    }

    /**
     * 
     * @param int $old_member_id
     */
    public function setOldMemberId($old_member_id) {
        $this->old_member_id = (int) $old_member_id;
    }

    /**
     * 
     * @param int $new_member_id
     */
    public function setNewMemberId($new_member_id) {
        $this->new_member_id = (int) $new_member_id;
    }

    /**
     * 
     * @param int $team_id
     */
    public function setTeamId($team_id) {
        $this->team_id = (int) $team_id;
    }

    /**
     * 
     * @param int $matchday_id
     */
    public function setMatchdayId($matchday_id) {
        $this->matchday_id = $matchday_id;
    }

    /**
     * 
     * @param boolean $constrained
     */
    public function setConstrained($constrained) {
        $this->constrained = (boolean) $constrained;
    }

        
    /**
     * Getter: giocatore
     * @return int
     */
    public function getGiocatoreOld() {
        if (empty($this->giocatoreOld))
            $this->giocatoreOld = Giocatore::getById($this->getIdGiocatoreOld());
        return $this->giocatoreOld;
    }

    /**
     * Getter: giocatore
     * @return int
     */
    public function getGiocatoreNew() {
        if (empty($this->giocatoreNew))
            $this->giocatoreNew = Giocatore::getById($this->getIdGiocatoreNew());
        return $this->giocatoreNew;
    }

    /**
     * Getter: utente
     * @return Utente
     */
    public function getUtente() {
        if (empty($this->utente))
            $this->utente = Utente::getById($this->getIdUtente());
        return $this->utente;
    }

    /**
     * Getter: giornata
     * @return Giornata
     */
    public function getGiornata() {
        if (empty($this->giornata))
            $this->giornata = Giornata::getById($this->getIdGiornata());
        return $this->giornata;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getId();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Transfert[]|Transfert|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Transfert
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Transfert[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Transfert[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
