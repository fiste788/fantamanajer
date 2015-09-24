<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Article;
use Fantamanajer\Models\Matchday;
use Fantamanajer\Models\Team;
use Lib\Database\Table;

abstract class ArticlesTable extends Table {

    const TABLE_NAME = "articles";

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $subtitle;

    /**
     *
     * @var string
     */
    public $body;

    /**
     *
     * @var \DateTime
     */
    public $created_at;

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

    public function __construct() {
        parent::__construct();
        $this->title = is_null($this->title) ? NULL : $this->getTitle();
        $this->subtitle = is_null($this->subtitle) ? NULL : $this->getSubtitle();
        $this->body = is_null($this->body) ? NULL : $this->getBody();
        $this->created_at = is_null($this->created_at) ? NULL : $this->getCreatedAt();
        $this->team_id = is_null($this->team_id) ? NULL : $this->getTeamId();
        $this->matchday_id = is_null($this->matchday_id) ? NULL : $this->getMatchdayId();
    }

    /**
     * 
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * 
     * @return string
     */
    public function getSubtitle() {
        return $this->subtitle;
    }

    /**
     * 
     * @return string
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getCreatedAt() {
        return (is_a($this->created_at, "DateTime")) ? $this->created_at : new \DateTime($this->created_at);
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
     * @return int
     */
    public function getMatchdayId() {
        return $this->matchday_id;
    }
    
    /**
     * 
     * @return Matchday
     */
    public function getMatchday() {
        if (empty($this->matchday)) {
            $this->matchday = Matchday::getById($this->getMatchdayId());
        }
        return $this->matchday;
    }

    /**
     * 
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * 
     * @param string $subtitle
     */
    public function setSubtitle($subtitle) {
        $this->subtitle = $subtitle;
    }

    /**
     * 
     * @param string $body
     */
    public function setBody($body) {
        $this->body = $body;
        return $this;
    }
    
    /**
     * 
     * @param \DateTime $created_at
     */
    public function setCreatedAt($created_at) {
        if (is_a($created_at, "DateTime")) {
            $this->created_at = $created_at;
        } else {
            $this->created_at = new \DateTime($created_at);
        }
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
     * @param Team $team
     */
    public function setTeam(Team $team) {
        $this->team_id = $team->getId();
        $this->team = $team;
    }

    /**
     * 
     * @param int $matchday_id
     */
    public function setMatchdayId($matchday_id) {
        $this->matchday_id = (int) $matchday_id;
    }

   /**
     * 
     * @param Matchday $matchday
     */
    public function setMatchday(Matchday $matchday) {
        $this->matchday_id = $matchday->getId();
        $this->matchday = $matchday;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getTitolo();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Article[]|Article|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Article
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Article[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Article[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
