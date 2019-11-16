<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\PublicKeyCredentialSource;
use Cake\Datasource\ModelAwareTrait;
use Webauthn\AttestedCredentialData;
use Webauthn\PublicKeyCredentialSource as WebauthnPublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialSourceRepository;
use Webauthn\PublicKeyCredentialUserEntity;

/**
 * Credentials Repo
 *
 * @property \App\Model\Table\PublicKeyCredentialSourcesTable $PublicKeyCredentialSources
 */
class CredentialRepositoryService implements PublicKeyCredentialSourceRepository
{
    use ModelAwareTrait;

    /**
     * Costructor
     */
    public function __construct()
    {
        $this->loadModel('PublicKeyCredentialSources');
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId credentialId
     * @return \App\Model\Entity\PublicKeyCredentialSource
     */
    private function findByCredentialId(string $credentialId): PublicKeyCredentialSource
    {
        return $this->PublicKeyCredentialSources->find()->where(['public_key_credential_id' => $credentialId])->first();
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId credentialId
     * @return bool
     */
    public function has(string $credentialId): bool
    {
        return $this->PublicKeyCredentialSources->exists(['public_key_credential_id' => $credentialId]);
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId id
     * @return \Webauthn\AttestedCredentialData
     */
    public function get(string $credentialId): AttestedCredentialData
    {
        $credential = $this->findByCredentialId($credentialId);

        return $credential->attested_credential_data;
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId id
     * @return string
     */
    public function getUserHandleFor(string $credentialId): string
    {
        $credential = $this->PublicKeyCredentialSources->find()->where([
            'public_key_credential_id' => base64_encode($credentialId),
        ])->first();

        return $credential->user_handle;
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId id
     * @return int
     */
    public function getCounterFor(string $credentialId): int
    {
        return $this->findByCredentialId($credentialId)->counter;
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId id
     * @param int $newCounter new value
     * @return void
     */
    public function updateCounterFor(string $credentialId, int $newCounter): void
    {
        $credential = $this->findByCredentialId($credentialId);
        $credential->counter = $newCounter;
        $this->PublicKeyCredentialSources->save($credential);
    }

    /**
     * Undocumented function
     *
     * @param string $publicKeyCredentialId arg
     * @return \Webauthn\PublicKeyCredentialSource|null
     */
    public function findOneByCredentialId(string $publicKeyCredentialId): ?WebauthnPublicKeyCredentialSource
    {
        $publicKeyCredential = $this->findByCredentialId($publicKeyCredentialId);

        return $publicKeyCredential != null ? $publicKeyCredential->toCredentialSource() : null;
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity arg
     * @return array
     */
    public function findAllForUserEntity(PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity): array
    {
        $sources = $this->PublicKeyCredentialSources->find()->where([
            'user_handle' => $publicKeyCredentialUserEntity->getId(),
        ]);

        return $sources->all()->map(function (PublicKeyCredentialSource $value) {
            return $value->toCredentialSource();
        })->toArray();
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialSource $publicKeyCredentialSource arg
     * @return void
     */
    public function saveCredentialSource(WebauthnPublicKeyCredentialSource $publicKeyCredentialSource): void
    {
        $entity = $this->findByCredentialId($publicKeyCredentialSource->getPublicKeyCredentialId());
        if ($entity == null) {
            $entity = $this->PublicKeyCredentialSources->newEntity();
        }
        $entity->fromCredentialSource($publicKeyCredentialSource);
        $this->PublicKeyCredentialSources->save($entity);
    }
}
