<?php

declare(strict_types=1);

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Stream;

use Cake\I18n\FrozenTime;

/**
 * Description of StreamSingleActivity
 *
 * @author Stefano
 */
abstract class StreamSingleActivity extends StreamActivity
{
    /**
     * Get time
     *
     * @return \Cake\I18n\FrozenTime
     */
    public function getTime(): FrozenTime
    {
        return $this->activity->offsetGet('time');
    }
}
