<?php

namespace App\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use PDO;

class SimpleArrayDataType extends Type
{

    public function toPHP($value, Driver $driver)
    {
        if ($value === null || $value == '') {
            return [];
        }

        return explode(',', $value);
    }

    public function marshal($value)
    {
        if ($value === null || $value == '') {
            return [];
        }

        return explode(',', $value);
    }

    public function toDatabase($value, Driver $driver)
    {
        if ($value === null || empty($value)) {
            return null;
        }

        return implode(',', $value);
    }

    public function toStatement($value, Driver $driver)
    {
        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }
}
