<?php
declare(strict_types=1);

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Stream;

use Cake\I18n\Time;

/**
 * Description of StreamSingleActivity
 *
 * @author Stefano
 */
abstract class StreamAggregatedActivity extends StreamActivity
{
    /**
     * Get time
     *
     * @return \Cake\I18n\Time
     */
    public function getTime(): Time
    {
        return $this->activity->offsetGet('updated_at');
    }
}
