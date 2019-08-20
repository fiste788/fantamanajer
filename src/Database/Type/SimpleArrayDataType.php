<?php
declare(strict_types=1);

namespace App\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\TypeInterface;
use PDO;

class SimpleArrayDataType implements TypeInterface
{
    public function toPHP($value, DriverInterface $driver)
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

    public function toDatabase($value, DriverInterface $driver)
    {
        if ($value === null || empty($value)) {
            return null;
        }

        return implode(',', $value);
    }

    public function toStatement($value, DriverInterface $driver)
    {
        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }

    public function getBaseType(): ?string
    {
    }

    /**
     * Returns type identifier name for this object.
     *
     * @return string|null The type identifier name for this object.
     */
    public function getName(): ?string
    {
    }

    /**
     * Generate a new primary key value for a given type.
     *
     * This method can be used by types to create new primary key values
     * when entities are inserted.
     *
     * @return mixed A new primary key value.
     * @see \Cake\Database\Type\UuidType
     */
    public function newId()
    {
    }
}
