<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\PublicKeyCredentialSource;
use Cake\Datasource\ModelAwareTrait;
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
     * @return \App\Model\Entity\PublicKeyCredentialSource|null
     */
    private function findByCredentialId(string $credentialId): ?PublicKeyCredentialSource
    {
        /** @var \App\Model\Entity\PublicKeyCredentialSource|null $pkcs */
        $pkcs = $this->PublicKeyCredentialSources->find()
            ->where(['public_key_credential_id' => $credentialId])
            ->first();

        return $pkcs;
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

        return $publicKeyCredential ? $publicKeyCredential->toCredentialSource() : null;
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity arg
     * @return \Webauthn\PublicKeyCredentialSource[]
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
        $entity = $this->findByCredentialId($publicKeyCredentialSource->getPublicKeyCredentialId()) ??
            $this->PublicKeyCredentialSources->newEmptyEntity();
        $entity->fromCredentialSource($publicKeyCredentialSource);
        $this->PublicKeyCredentialSources->save($entity);
    }
}
