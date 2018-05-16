<?php

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->shell('fantamanajer lineup send_email -n')
    ->every('minute', 3)
    ->description('Send lineup summary')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'send_lineup_summary' . DS . date('yyyyddmm') . '.log');

return $schedule;
