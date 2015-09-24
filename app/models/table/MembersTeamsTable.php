<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Member;
use Fantamanajer\Models\Team;
use Lib\Database\Table;

abstract class MembersTeamsTable extends Table {

    const TABLE_NAME = 'members_teams';

    /**
     *
     * @var int
     */
    public $member_id;

    /**
     *
     * @var int
     */
    public $team_id;

    public function __construct() {
        parent::__construct();
        $this->member_id = is_null($this->member_id) ? NULL : $this->getMemberId();
        $this->team_id = is_null($this->team_id) ? NULL : $this->getTeamId();
    }

    /**
     * 
     * @return int
     */
    public function getMemberId() {
        return (int) $this->member_id;
    }
    
    /**
     * 
     * @return Member
     */
    public function getMember() {
        if (empty($this->member)) {
            $this->member = Member::getById($this->getMemberId());
        }
        return $this->member;
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
     * @return Team
     */
    public function getTeam() {
        if (empty($this->team)) {
            $this->team = Team::getById($this->getTeamId());
        }
        return $this->team;
    }

    /**
     * 
     * @param int $member_id
     */
    public function setMemberId($member_id) {
        $this->member_id = (int) $member_id;
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
     * @param Member $member
     * @return void
     */
    public function setMember($member) {
        $this->member = $member;
        $this->setMemberId($member->getId());
    }

    /**
     * 
     * @param Team $team
     * @return void
     */
    public function setUtente($team) {
        $this->team = $team;
        $this->setTeamId($team->getId());
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->id;
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return MemberTeam[]|MemberTeam|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return MemberTeam
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return MemberTeam[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return MemberTeam[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
