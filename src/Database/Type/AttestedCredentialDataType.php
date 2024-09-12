<?php

declare(strict_types=1);

namespace App\Database\Type;

use AllowDynamicProperties;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Database\Driver;
use Cake\Database\Type\BaseType;
use Webauthn\AttestedCredentialData;

/**
 * AttestedCredential data type
 *
 * @property \App\Service\WebauthnService $Webauthn
 */
#[AllowDynamicProperties]
class AttestedCredentialDataType extends BaseType
{
    use ServiceAwareTrait;

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

        $this->loadService('Webauthn');

        return $this->Webauthn->serializer->deserialize(
            $json,
            AttestedCredentialData::class,
            'json'
        );
        //return AttestedCredentialData::createFromArray($json);
    }

    /**
     * @inheritDoc
     */
    public function toDatabase(mixed $value, Driver $driver): mixed
    {
        return json_encode($value);
    }
}
