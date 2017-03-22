<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Member;
use Fantamanajer\Models\Team;
use Lib\Database\Table;

abstract class SelectionsTable extends Table {

    const TABLE_NAME = 'selections';

    /**
     *
     * @var int
     */
    public $number_selections;

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

    public function __construct() {
        parent::__construct();
        $this->number_selections = is_null($this->number_selections) ? NULL : $this->getNumberSelections();
        $this->old_member_id = is_null($this->old_member_id) ? NULL : $this->getOldMemberId();
        $this->new_member_id = is_null($this->new_member_id) ? NULL : $this->getNewMemberId();
        $this->team_id = is_null($this->team_id) ? NULL : $this->getTeamId();
    }

    /**
     * 
     * @return int
     */
    public function getNumberSelections() {
        return (int) $this->number_selections;
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
        return $this->team_id;
    }

    /**
     * 
     * @param type $number_selections
     */
    public function setNumberSelections($number_selections) {
        $this->number_selections = (int) $number_selections;
    }

    /**
     * 
     * @param int $old_member_id
     */
    public function setOldMemberId($old_member_id) {
        $this->old_member_id = $old_member_id;
    }

    /**
     * 
     * @param int $new_member_id
     */
    public function setNewMemberId($new_member_id) {
        $this->new_member_id = $new_member_id;
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
     * @param Member $member_old
     * @return void
     */
    public function setMemberOld($member_old) {
        $this->member_old = $member_old;
        $this->setOldMemberId(is_null($member_old) ? NULL : $member_old->getId());
    }

    /**
     * 
     * @param Member $member_new
     * @return void
     */
    public function setMemberNew($member_new) {
        $this->member_new = $member_new;
        $this->setNewMemberId(is_null($member_new) ? NULL : $member_new->getId());
    }

    /**
     * 
     * @param Team $team
     * @return void
     */
    public function setTeam($team) {
        $this->team = $team;
        $this->setTeamId($team->getId());
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
     * @return Member
     */
    public function getMemberNew() {
        if (empty($this->member_new)) {
            $this->member_new = Member::getById($this->getMemberNewId());
        }
        return $this->member_new;
    }

    /**
     * 
     * @return Member
     */
    public function getMemberOld() {
        if (empty($this->member_old)) {
            $this->member_old = Member::getById($this->getMemberOldId());
        }
        return $this->member_old;
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
     * @return Selection[]|Selection|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Selection
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Selection[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Selection[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
