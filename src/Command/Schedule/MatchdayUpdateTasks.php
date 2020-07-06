<?php

declare(strict_types=1);

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();

$schedule
    ->shell('update_matchday -n -v')
    ->every('hour', 6)
    ->description('Matchday update')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'matchday_update' . DS . date('ymd') . '.log');

return $schedule;
