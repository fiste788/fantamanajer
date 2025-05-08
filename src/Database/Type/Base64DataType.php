<?php
declare(strict_types=1);

namespace App\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\BaseType;
use Override;

class Base64DataType extends BaseType
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function marshal(mixed $value): mixed
    {
        if (is_array($value) || $value === null) {
            return $value;
        }

        return base64_decode((string)$value, true);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toPHP(mixed $value, Driver $driver): mixed
    {
        if ($value === null) {
            return null;
        }

        return base64_decode((string)$value, true);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function toDatabase(mixed $value, Driver $driver): mixed
    {
        return base64_encode((string)$value);
    }
}
