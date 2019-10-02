<?php

declare(strict_types=1);

namespace App\Database\Type;

use PDO;
use Cake\Database\TypeInterface;
use Cake\Database\DriverInterface;

class Base64DataType implements TypeInterface
{
    public function toPHP($value, DriverInterface $driver)
    {
        if ($value === null) {
            return null;
        }

        return base64_decode($value, true);
    }

    public function marshal($value)
    {
        if (is_array($value) || $value === null) {
            return $value;
        }

        return base64_decode($value, true);
    }

    public function toDatabase($value, DriverInterface $driver)
    {
        return base64_encode($value);
    }

    public function toStatement($value, DriverInterface $driver)
    {
        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }

    public function getBaseType(): ?string
    { }

    /**
     * Returns type identifier name for this object.
     *
     * @return string|null The type identifier name for this object.
     */
    public function getName(): ?string
    { }

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
    { }
}
