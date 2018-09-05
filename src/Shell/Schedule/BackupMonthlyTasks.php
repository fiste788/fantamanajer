<?php

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->shell('backup export -r 13 -c gzip -f monthly' . DS . '{$DATABASE}_{$DATETIME}.sql.gz')
    ->monthly()
    ->description('Monthly backup')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'backup' . DS . date('ymd') . '.log');

return $schedule;
