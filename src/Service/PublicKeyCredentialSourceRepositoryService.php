<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\PublicKeyCredentialSource;
use Cake\ORM\Locator\LocatorAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;
use Webauthn\PublicKeyCredentialSource as WebauthnPublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialUserEntity;

/**
 * Credentials Repo
 */
/** @psalm-suppress DeprecatedInterface */
class PublicKeyCredentialSourceRepositoryService
{
    use LocatorAwareTrait;

    /**
     * Undocumented function
     *
     * @param string $credentialId credentialId
     * @throws \Cake\Core\Exception\CakeException
     * @return \App\Model\Entity\PublicKeyCredentialSource|null
     */
    private function findByCredentialId(string $credentialId): ?PublicKeyCredentialSource
    {
        /** @var \App\Model\Entity\PublicKeyCredentialSource|null $pkcs */
        $pkcs = $this->fetchTable('PublicKeyCredentialSources')->find()
            ->where(['public_key_credential_id' => $credentialId])
            ->first();

        return $pkcs;
    }

    /**
     * Undocumented function
     *
     * @param string $publicKeyCredentialId arg
     * @throws \Cake\Core\Exception\CakeException
     * @return \Webauthn\PublicKeyCredentialSource|null
     */
    public function findOneByCredentialId(
        SerializerInterface $serializer,
        string $publicKeyCredentialId,
    ): ?WebauthnPublicKeyCredentialSource {
        $publicKeyCredential = $this->findByCredentialId($publicKeyCredentialId);

        return $publicKeyCredential ? $publicKeyCredential->toCredentialSource($serializer) : null;
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity arg
     * @return array<\Webauthn\PublicKeyCredentialSource>
     * @throws \RuntimeException
     * @psalm-return array<array-key, \Webauthn\PublicKeyCredentialSource>
     */
    public function findAllForUserEntity(
        SerializerInterface $serializer,
        PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity,
    ): array {
        $sources = $this->fetchTable('PublicKeyCredentialSources')->find()->where([
            'user_handle' => $publicKeyCredentialUserEntity->id,
        ]);

        /** @var array<\Webauthn\PublicKeyCredentialSource> $credentials */
        $credentials = $sources->all()->map(function (PublicKeyCredentialSource $value) use ($serializer) {
            return $value->toCredentialSource($serializer);
        })->toList();

        return $credentials;
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialSource $publicKeyCredentialSource arg
     * @throws \Cake\Core\Exception\CakeException
     * @throws \TypeError
     * @throws \RangeException
     * @return void
     */
    public function saveCredentialSource(
        SerializerInterface $serializer,
        WebauthnPublicKeyCredentialSource $publicKeyCredentialSource,
    ): void {
        /** @var \App\Model\Entity\PublicKeyCredentialSource $entity */
        $entity = $this->findByCredentialId($publicKeyCredentialSource->publicKeyCredentialId) ??
            $this->fetchTable('PublicKeyCredentialSources')->newEmptyEntity();
        $entity->fromCredentialSource($serializer, $publicKeyCredentialSource);
        $this->fetchTable('PublicKeyCredentialSources')->save($entity);
    }
}
