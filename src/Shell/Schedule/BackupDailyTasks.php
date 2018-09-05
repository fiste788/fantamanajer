<?php

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->shell('backup export -r 8 -c gzip -f daily' . DS . '{$DATABASE}_{$DATETIME}.sql.gz')
    ->dailyAt('02:00')
    ->description('Daily backup')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'backup' . DS . date('ymd') . '.log');

return $schedule;
