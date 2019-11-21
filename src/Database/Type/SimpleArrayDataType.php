<?php

declare(strict_types=1);

namespace App\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\TypeInterface;
use PDO;

class SimpleArrayDataType implements TypeInterface
{
    /**
     * @inheritDoc
     */
    public function toPHP($value, DriverInterface $driver)
    {
        if ($value === null || $value == '') {
            return [];
        }

        return explode(',', $value);
    }

    /**
     * @inheritDoc
     */
    public function marshal($value)
    {
        if ($value === null || $value == '') {
            return [];
        }

        return explode(',', $value);
    }

    /**
     * @inheritDoc
     */
    public function toDatabase($value, DriverInterface $driver)
    {
        if ($value === null || empty($value)) {
            return null;
        }

        return implode(',', $value);
    }

    /**
     * @inheritDoc
     */
    public function toStatement($value, DriverInterface $driver)
    {
        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }

    /**
     * @inheritDoc
     */
    public function getBaseType(): ?string
    {
        return "string";
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    { }

    /**
     * @inheritDoc 
     *
     * @return void
     */
    public function newId()
    { }
}
