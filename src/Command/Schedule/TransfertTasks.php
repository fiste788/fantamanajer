<?php

declare(strict_types=1);

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();

$schedule
    ->shell('transfert -n')
    ->dailyAt('01:00')
    ->description('Transfert')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'transfert' . DS . date('ymd') . '.log');

return $schedule;
