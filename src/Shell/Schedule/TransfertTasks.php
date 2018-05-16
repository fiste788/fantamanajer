<?php

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->shell('fantamanajer transfert -n')
    ->dailyAt('02:00')
    ->description('Transfert')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'weekly_script' . DS . date('yyyyddmm') . '.log');

return $schedule;
