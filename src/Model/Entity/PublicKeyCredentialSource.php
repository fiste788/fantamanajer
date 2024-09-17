<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Webauthn\Denormalizer\PublicKeyCredentialSourceDenormalizer;
use Webauthn\Denormalizer\TrustPathDenormalizer;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\PublicKeyCredentialSource as WebauthnPublicKeyCredentialSource;
use Webauthn\TrustPath\TrustPath;

/**
 * PublicKeyCredentialSource Entity
 *
 * @property string $id
 * @property string $public_key_credential_id
 * @property string $type
 * @property string[] $transports
 * @property string $attestation_type
 * @property string $trust_path
 * @property string $aaguid
 * @property string $credential_public_key
 * @property string $user_handle
 * @property int $counter
 * @property string|null $name
 * @property string|null $user_agent
 * @property \Cake\I18n\DateTime $created_at
 * @property \Cake\I18n\DateTime $last_seen_at
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
     * @var array<string, bool>
     */
    protected array $_accessible = [
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
        'last_seen_at' => true,
        'user' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var list<string>
     */
    protected array $_hidden = [
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
    public function toCredentialSource(SerializerInterface $serializer): WebauthnPublicKeyCredentialSource
    {
        return $serializer->deserialize([
            'publicKeyCredentialId' => $this->public_key_credential_id,
            'type' => $this->type,
            'transports' => $this->transports,
            'attestationType' => $this->attestation_type,
            'trustPath' => $this->trust_path,
            'aaguid' => $this->aaguid,
            'credentialPublicKey' => $this->credential_public_key,
            'userHandle' => $this->user_handle,
            'counter' => $this->counter
        ], WebauthnPublicKeyCredentialSource::class, 'json');
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialSource $credentialSource Credential source
     * @return $this
     */
    public function fromCredentialSource(SerializerInterface $serializer, WebauthnPublicKeyCredentialSource $credentialSource)
    {
        $this->public_key_credential_id = $credentialSource->publicKeyCredentialId;
        $this->type = $credentialSource->type;
        $this->transports = $credentialSource->transports;
        $this->attestation_type = $credentialSource->attestationType;
        $this->trust_path = $serializer->serialize($credentialSource->trustPath, TrustPath::class);
        $this->aaguid =  $credentialSource->aaguid->toRfc4122();
        $this->credential_public_key = Base64UrlSafe::encodeUnpadded($credentialSource->credentialPublicKey);
        $this->user_handle = Base64UrlSafe::encodeUnpadded($credentialSource->userHandle);;
        $this->counter = $credentialSource->counter;

        return $this;
    }
}
