<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\PublicKeyCredentialSource;
use Cake\ORM\Locator\LocatorAwareTrait;
use Webauthn\PublicKeyCredentialSource as WebauthnPublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialSourceRepository;
use Webauthn\PublicKeyCredentialUserEntity;

/**
 * Credentials Repo
 */
class PublicKeyCredentialSourceRepositoryService implements PublicKeyCredentialSourceRepository
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
    public function findOneByCredentialId(string $publicKeyCredentialId): ?WebauthnPublicKeyCredentialSource
    {
        $publicKeyCredential = $this->findByCredentialId($publicKeyCredentialId);

        return $publicKeyCredential ? $publicKeyCredential->toCredentialSource() : null;
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity arg
     * @return \Webauthn\PublicKeyCredentialSource[]
     * @throws \RuntimeException
     * @psalm-return array<array-key, \Webauthn\PublicKeyCredentialSource>
     */
    public function findAllForUserEntity(PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity): array
    {
        $sources = $this->fetchTable('PublicKeyCredentialSources')->find()->where([
            'user_handle' => $publicKeyCredentialUserEntity->getId(),
        ]);

        /** @var \Webauthn\PublicKeyCredentialSource[] $credentials */
        $credentials = $sources->all()->map(function (PublicKeyCredentialSource $value) {
            return $value->toCredentialSource();
        })->toList();

        return $credentials;
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialSource $publicKeyCredentialSource arg
     * @throws \Cake\Core\Exception\CakeException
     * @return void
     */
    public function saveCredentialSource(WebauthnPublicKeyCredentialSource $publicKeyCredentialSource): void
    {
        /** @var \App\Model\Entity\PublicKeyCredentialSource $entity */
        $entity = $this->findByCredentialId($publicKeyCredentialSource->getPublicKeyCredentialId()) ??
            $this->fetchTable('PublicKeyCredentialSources')->newEmptyEntity();
        $entity->fromCredentialSource($publicKeyCredentialSource);
        $this->fetchTable('PublicKeyCredentialSources')->save($entity);
    }
}
