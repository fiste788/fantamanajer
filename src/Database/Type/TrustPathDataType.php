<?php
declare(strict_types=1);

namespace App\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\TypeInterface;
use PDO;
use Webauthn\TrustPath\TrustPathLoader;

class TrustPathDataType implements TypeInterface
{
    /**
     * @inheritDoc
     */
    public function toPHP($value, DriverInterface $driver)
    {
        if ($value === null) {
            return null;
        }
        $json = json_decode($value, true);

        return TrustPathLoader::loadTrustPath($json);
    }

    /**
     * @inheritDoc
     */
    public function marshal($value)
    {
        if (is_array($value) || $value === null) {
            return $value;
        }

        return json_decode($value, true);
    }

    /**
     * @inheritDoc
     */
    public function toDatabase($value, DriverInterface $driver)
    {
        return json_encode($value);
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
    {
        return "trustpathdata";
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function newId()
    {
    }
}