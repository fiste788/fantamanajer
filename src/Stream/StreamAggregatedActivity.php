<?php
declare(strict_types=1);

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Stream;

use Cake\I18n\DateTime;
use RuntimeException;

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
     * @return \Cake\I18n\DateTime
     * @throws \RuntimeException
     */
    public function getTime(): DateTime
    {
        /** @var \Cake\I18n\DateTime|null $res */
        $res = $this->activity->offsetGet('updated_at');
        if ($res) {
            return $res;
        }

        throw new RuntimeException('Unable to determine updated at activity time');
    }
}
