<?php

namespace Fantamanajer\Models\View;

use Fantamanajer\Models\Club;
use Fantamanajer\Models\Disposition;
use Fantamanajer\Models\Matchday;
use Fantamanajer\Models\Member;
use Fantamanajer\Models\Player;
use Fantamanajer\Models\Rating;
use Fantamanajer\Models\Role;
use Fantamanajer\Models\Team;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;

class RatingDetails extends Rating {

    const TABLE_NAME = "view_0_lineups_details";

    /**
     *
     * @var Member 
     */
    var $member;
    
    /**
     *
     * @var Club
     */
    var $club;
    
    /**
     *
     * @var Role
     */
    var $role;
    
    /**
     *
     * @var Player
     */
    var $player;
    
    /**
     *
     * @var Disposition 
     */
    var $disposition;

    function __construct() {
        parent::__construct();
        $this->member = new Member();
        $this->member->getFromObject($this);
        $this->club = new Club();
        $this->club->getFromObject($this);
        $this->player = new Player();
        $this->player->getFromObject($this);
        $this->role = new Role();
        $this->role->getFromObject($this);
        $this->disposition = new Disposition();
        $this->disposition->getFromObject($this);
    }
    
    public static function getByMatchdayAndTeam(Matchday $matchday, Team $team) {
        $q = "SELECT *
		FROM " . self::TABLE_NAME . " 
		WHERE matchday_id = :matchday_id AND team_id = :team_id 
                ORDER BY disposition_position";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":matchday_id", $matchday->getId(), PDO::PARAM_INT);
        $exe->bindValue(":team_id", $team->getId(), PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    }

}
