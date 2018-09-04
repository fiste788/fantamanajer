<?php

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->run(ROOT . DS . 'bin' . DS . 'backup.sh daily')
    ->dailyAt('02:00')
    ->description('Backup')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'backup' . DS . date('ymd') . '.log');

return $schedule;
