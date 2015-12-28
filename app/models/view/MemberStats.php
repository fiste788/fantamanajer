<?php

namespace Fantamanajer\Models\View;

use Fantamanajer\Models\Championship;
use Fantamanajer\Models\Club;
use Fantamanajer\Models\Member;
use Fantamanajer\Models\Player;
use Fantamanajer\Models\Role;
use Fantamanajer\Models\Team;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;

class MemberStats extends Member {

    const TABLE_NAME = "view_1_members_stats";

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
    
    var $sum_present;
    var $sum_valued;
    var $avg_points;
    var $avg_rating;
    var $sum_goals;
    var $sum_goals_against;
    var $sum_assist;
    var $sum_yellow_card;
    var $sum_red_card;
    var $quotation;

    function __construct() {
        parent::__construct();
        $this->club = new Club();
        $this->club->getFromObject($this);
        $this->player = new Player();
        $this->player->getFromObject($this);
        $this->role = new Role();
        $this->role->getFromObject($this);
        $this->sum_present = $this->getSumPresent();
        $this->sum_valued = $this->getSumValued();
        $this->avg_points = $this->getAvgPoints();
        $this->avg_rating = $this->getAvgRating();
        $this->sum_goals = $this->getSumGoals();
        $this->sum_goals_against = $this->getSumGoalsAgainst();
        $this->sum_assist = $this->getSumAssist();
        $this->sum_yellow_card = $this->getSumYellowCard();
        $this->sum_red_card = $this->getSumRedCard();
        $this->quotation = $this->getQuotation();
    }

    /**
     * 
     * @return int
     */
    public function getSumPresent() {
        return (int) $this->sum_present;
    }

    /**
     * 
     * @return int
     */
    public function getSumValued() {
        return (int) $this->sum_valued;
    }

    /**
     * 
     * @return double
     */
    public function getAvgPoints() {
        return (double) $this->avg_points;
    }

    /**
     * 
     * @return double
     */
    public function getAvgRating() {
        return (double) $this->avg_rating;
    }

    /**
     * 
     * @return int
     */
    public function getSumGoals() {
        return (int) $this->sum_goals;
    }

    /**
     * 
     * @return int
     */
    public function getSumGoalsAgainst() {
        return (int) $this->sum_goals_against;
    }

    /**
     * 
     * @return int
     */
    public function getSumAssist() {
        return (int) $this->sum_assist;
    }

    /**
     * 
     * @return int
     */
    public function getSumYellowCard() {
        return (int) $this->sum_yellow_card;
    }

    /**
     * 
     * @return int
     */
    public function getSumRedCard() {
        return (int) $this->sum_red_card;
    }

    /**
     * 
     * @return int
     */
    public function getQuotation() {
        return (int) $this->quotation;
    }
    
    public static function getByTeam(Team $team) {
        $q = "SELECT " . self::TABLE_NAME . ".* "
                . "FROM " . self::TABLE_NAME . " JOIN members_teams ON " . self::TABLE_NAME . ".id = members_teams.member_id "
                . "WHERE team_id = :team_id";
        //die($q);
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':team_id', $team->getId(), PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $values[$obj->getId()] = $obj;
        }
        return $values;
    }
    
    public static function getFree(Role $role, Championship $championship) {
        $q = "SELECT " . self::TABLE_NAME . ".*
		FROM " . self::TABLE_NAME . " 
		WHERE id NOT IN (
                    SELECT member_id
                    FROM members_teams
                    WHERE team_id IN (
                        SELECT team_id
                        FROM teams
                        WHERE championship_id = :championship_id
                    )
                )";
        if ($role != NULL) {
            $q .= " AND role_id = :role_id";
        }
        $q .= " AND active = :active
		ORDER BY player_surname, player_name";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":championship_id", $championship->getId(), PDO::PARAM_INT);
        if($role != null) {
            $exe->bindValue(":role_id", $role->getId(),PDO::PARAM_INT);
        }
        $exe->bindValue(":active", TRUE, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $values[$obj->getId()] = $obj;
        }
        return $values;
    }
}
