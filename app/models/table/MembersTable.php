<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Club;
use Fantamanajer\Models\Member;
use Fantamanajer\Models\Player;
use Lib\Database\Table;

abstract class MembersTable extends Table {

    const TABLE_NAME = "members";

     /**
     *
     * @var string
     */
    public $code_gazzetta;
   
    /**
     *
     * @var boolean
     */
    public $active;
    
    /**
     *
     * @var int
     */
    public $role_id;
    
    /**
     *
     * @var int
     */
    public $player_id;
    
    /**
     *
     * @var int
     */
    public $club_id;

    /**
     *
     * @var int
     */
    public $season_id;

    public function __construct() {
        parent::__construct();
        $this->code_gazzetta = is_null($this->code_gazzetta) ? NULL : $this->getCodeGazzetta();
        $this->active = is_null($this->active) ? NULL : $this->isActive();
        $this->role_id = is_null($this->role_id) ? NULL : $this->getRoleId();
        $this->player_id = is_null($this->player_id) ? NULL : $this->getPlayerId();
        $this->club_id = is_null($this->club_id) ? NULL : $this->getClubId();
        $this->season_id = is_null($this->season_id) ? NULL : $this->getSeasonId();
    }

    /**
     * 
     * @return string
     */
    public function getCodeGazzetta() {
        return (int) $this->code_gazzetta;
    }

    /**
     * 
     * @return boolean
     */
    public function isActive() {
        return (boolean) $this->active;
    }

    /**
     * 
     * @return int
     */
    public function getRoleId() {
        return (int) $this->role_id;
    }

    /**
     * 
     * @return int
     */
    public function getPlayerId() {
        return (int) $this->player_id;
    }
    
    /**
     * 
     * @return Player
     */
    public function getPlayer() {
        if (empty($this->player)) {
            $this->player = Player::getById($this->getPlayerId());
        }
        return $this->player;
    }

    /**
     * 
     * @return int
     */
    public function getClubId() {
        return (int) $this->club_id;
    }
    
    /**
     * 
     * @return Club
     */
    public function getClub() {
        if (empty($this->club)) {
            $this->club = Club::getById($this->getClubId());
        }
        return $this->club;
    }

    /**
     * 
     * @return int
     */
    public function getSeasonId() {
        return (int) $this->season_id;
    }

    /**
     * 
     * @param int $code_gazzetta
     */
    public function setCodeGazzetta($code_gazzetta) {
        $this->code_gazzetta = (int) $code_gazzetta;
    }

    /**
     * 
     * @param boolean $active
     */
    public function setActive($active) {
        $this->active = (boolean) $active;
    }

    /**
     * 
     * @param int $role_id
     */
    public function setRoleId($role_id) {
        $this->role_id = (int) $role_id;
    }

    /**
     * 
     * @param int $player_id
     */
    public function setPlayerId($player_id) {
        $this->player_id = (int) $player_id;
    }

    /**
     * 
     * @param int $club_id
     */
    public function setClubId($club_id) {
        $this->club_id = (int) $club_id;
    }

    /**
     * 
     * @param int $season_id
     */
    public function setSeasonId($season_id) {
        $this->season_id = (int) $season_id;
    }

    
    /**
     *
     * @return string
     */
    public function __toString() {
        return (string) $this->getId();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Member[]|Member|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Member
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Member[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Member[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
