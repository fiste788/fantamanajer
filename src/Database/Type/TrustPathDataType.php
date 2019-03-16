<?php

namespace App\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use PDO;
use Webauthn\AttestedCredentialData;

class TrustPathDataType extends Type
{

    public function toPHP($value, Driver $driver)
    {
        if ($value === null) {
            return null;
        }
        $json = \Safe\json_decode($value, true);

        return AttestedCredentialData::createFromJson($json);
    }

    public function marshal($value)
    {
        if (is_array($value) || $value === null) {
            return $value;
        }

        return \Safe\json_decode($value, true);
    }

    public function toDatabase($value, Driver $driver)
    {
        return \Safe\json_encode($value);
    }

    public function toStatement($value, Driver $driver)
    {
        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }
}
