<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\SeasonsTable;

class Season extends SeasonsTable {

    /**
     *
     * @return Season
     */
    public static function getCurrent() {
        return Matchday::getCurrent()->getSeason();
    }

}

 