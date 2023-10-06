<?php
declare(strict_types=1);

namespace App\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\BaseType;
use Webauthn\TrustPath\TrustPathLoader;

class TrustPathDataType extends BaseType
{
    /**
     * @inheritDoc
     */
    public function marshal(mixed $value): mixed
    {
        if (is_array($value) || $value === null) {
            return $value;
        }

        return json_decode((string)$value, true);
    }

    /**
     * @inheritDoc
     */
    public function toPHP(mixed $value, Driver $driver): mixed
    {
        if ($value === null) {
            return null;
        }
        /** @var array<string, mixed> $json */
        $json = json_decode((string)$value, true);

        return TrustPathLoader::loadTrustPath($json);
    }

    /**
     * @inheritDoc
     */
    public function toDatabase(mixed $value, Driver $driver): mixed
    {
        return json_encode($value);
    }
}
