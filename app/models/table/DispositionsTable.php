<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Lineup;
use Fantamanajer\Models\Member;
use Fantamanajer\Models\Disposition;
use Lib\Database\Table;

abstract class DispositionTable extends Table {

    const TABLE_NAME = 'disposition';

    /**
     *
     * @var int
     */
    public $position;

    /**
     *
     * @var int 0 = non ha giocato, 1 = giocato, 2 = capitano
     */
    public $consideration;
    
    /**
     *
     * @var int
     */
    public $lineup_id;

    /**
     *
     * @var int
     */
    public $member_id;

    public function __construct() {
        parent::__construct();
        $this->position = is_null($this->position) ? NULL : $this->getPosition();
        $this->consideration = is_null($this->consideration) ? NULL : $this->getConsideration();
        $this->lineup_id = is_null($this->lineup_id) ? NULL : $this->getLineupId();
        $this->member_id = is_null($this->member_id) ? NULL : $this->getMemberId();
    }

    /**
     * 
     * @return int
     */
    public function getPosition() {
        return (int) $this->position;
    }

    /**
     * 
     * @return int
     */
    public function getConsideration() {
        return (int) $this->consideration;
    }

    /**
     * 
     * @return int
     */
    public function getLineupId() {
        return (int) $this->lineup_id;
    }
    
    /**
     * 
     * @return Lineup
     */
    public function getLineup() {
        if (empty($this->lineup)) {
            $this->lineup = Lineup::getById($this->getLineupId());
        }
        return $this->lineup;
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
     * @param int $position
     */
    public function setPosition($position) {
        $this->position = (int) $position;
    }

    /**
     * 
     * @param int $consideration
     */
    public function setConsideration($consideration) {
        $this->consideration = (int) $consideration;
    }

    /**
     * 
     * @param int $lineup_id
     */
    public function setLineupId($lineup_id) {
        $this->lineup_id = $lineup_id;
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
     * @return string
     */
    public function __toString() {
        return $this->getMemberId();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Disposition[]|Disposition|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Disposition
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Disposition[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Disposition[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
