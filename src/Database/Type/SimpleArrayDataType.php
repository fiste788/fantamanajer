<?php
declare(strict_types=1);

namespace App\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\Type\BaseType;

class SimpleArrayDataType extends BaseType
{
    /**
     * @inheritDoc
     */
    public function marshal($value)
    {
        if ($value === null || $value == '') {
            return [];
        }

        return explode(',', (string)$value);
    }

    /**
     * @inheritDoc
     */
    public function toPHP($value, DriverInterface $driver)
    {
        if ($value === null || $value == '') {
            return [];
        }

        return explode(',', (string)$value);
    }

    /**
     * @inheritDoc
     */
    public function toDatabase($value, DriverInterface $driver)
    {
        if ($value === null || empty($value) || !is_array($value)) {
            return null;
        }

        /** @var array<array-key, scalar> $val */
        $val = $value;

        return implode(',', $val);
    }
}
