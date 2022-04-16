<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Symfony\Component\Uid\Uuid;
use Webauthn\PublicKeyCredentialSource as WebauthnPublicKeyCredentialSource;

/**
 * PublicKeyCredentialSource Entity
 *
 * @property string $id
 * @property string $public_key_credential_id
 * @property string $type
 * @property string[] $transports
 * @property string $attestation_type
 * @property \Webauthn\TrustPath\TrustPath $trust_path
 * @property string $aaguid
 * @property string $credential_public_key
 * @property string $user_handle
 * @property int $counter
 * @property string|null $name
 * @property string|null $user_agent
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \App\Model\Entity\User $user
 */
class PublicKeyCredentialSource extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var bool[]
     */
    protected $_accessible = [
        'id' => true,
        'public_key_credential_id' => true,
        'type' => false,
        'transports' => false,
        'attestation_type' => false,
        'trust_path' => false,
        'aaguid' => false,
        'credential_public_key' => false,
        'user_handle' => false,
        'counter' => true,
        'name' => true,
        'user_agent' => true,
        'created_at' => false,
        'user' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var string[]
     */
    protected $_hidden = [
        'attestation_type',
        'transports',
        'trust_path',
        'aaguid',
        'credential_public_key',
    ];

    /**
     * Undocumented function
     *
     * @return \Webauthn\PublicKeyCredentialSource
     * @throws \InvalidArgumentException
     */
    public function toCredentialSource(): WebauthnPublicKeyCredentialSource
    {
        $aaguid = Uuid::fromString($this->aaguid);

        return new WebauthnPublicKeyCredentialSource(
            $this->public_key_credential_id,
            $this->type,
            $this->transports,
            $this->attestation_type,
            $this->trust_path,
            $aaguid,
            $this->credential_public_key,
            $this->user_handle,
            $this->counter
        );
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialSource $credentialSource Credential source
     * @return $this
     */
    public function fromCredentialSource(WebauthnPublicKeyCredentialSource $credentialSource)
    {
        $this->public_key_credential_id = $credentialSource->getPublicKeyCredentialId();
        $this->type = $credentialSource->getType();
        $this->transports = $credentialSource->getTransports();
        $this->attestation_type = $credentialSource->getAttestationType();
        $this->trust_path = $credentialSource->getTrustPath();
        $this->aaguid = $credentialSource->getAaguid()->toRfc4122();
        $this->credential_public_key = $credentialSource->getCredentialPublicKey();
        $this->user_handle = $credentialSource->getUserHandle();
        $this->counter = $credentialSource->getCounter();

        return $this;
    }
}
