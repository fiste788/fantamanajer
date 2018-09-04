<?php

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->shell('update_matchday -n')
    ->every('hour', 6)
    ->description('Matchday update')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'matchday_update' . DS . date('ymd') . '.log');

return $schedule;
