<?php
declare(strict_types=1);

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Stream;

/**
 * Description of StreamSingleActivity
 *
 * @author Stefano
 */
abstract class StreamAggregatedActivity extends StreamActivity
{
    public function getTime()
    {
        return $this->activity['updated_at'];
    }
}
