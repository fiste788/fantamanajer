<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Symfony\Component\Serializer\SerializerInterface;
use Webauthn\PublicKeyCredentialSource as WebauthnPublicKeyCredentialSource;

/**
 * PublicKeyCredentialSource Entity
 *
 * @property string $id
 * @property string $public_key_credential_id
 * @property string $type
 * @property string|null $transports
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
     * @throws \TypeError
     * @throws \RangeException
     */
    public function toCredentialSource(SerializerInterface $serializer): WebauthnPublicKeyCredentialSource
    {
        return $serializer->deserialize(json_encode([
            'publicKeyCredentialId' => $this->public_key_credential_id,
            'type' => $this->type,
            'transports' => json_decode($this->transports != null ? $this->transports : ''),
            'attestationType' => $this->attestation_type,
            'trustPath' => json_decode($this->trust_path),
            'aaguid' => $this->aaguid,
            'credentialPublicKey' => $this->credential_public_key,
            'userHandle' => Base64UrlSafe::encode($this->user_handle),
            'counter' => $this->counter,
        ]), WebauthnPublicKeyCredentialSource::class, 'json');
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialSource $credentialSource Credential source
     * @return $this
     * @throws \TypeError
     * @throws \RangeException
     */
    public function fromCredentialSource(
        SerializerInterface $serializer,
        WebauthnPublicKeyCredentialSource $credentialSource,
    ) {
        /** @var array<string, string> $json */
        $json = json_decode($serializer->serialize($credentialSource, 'json'), true);
        $transports = json_encode($json['transports']);
        $trustPath = json_encode($json['trustPath']);
        $this->public_key_credential_id = $json['publicKeyCredentialId'];
        $this->type = $json['type'];
        $this->transports = $transports != false ? $transports : '';
        $this->attestation_type = $json['attestationType'];
        $this->trust_path = $trustPath != false ? $trustPath : '';
        $this->aaguid = $json['aaguid'];
        $this->credential_public_key = $json['credentialPublicKey'];
        $this->user_handle = Base64UrlSafe::decode($json['userHandle']);
        $this->counter = (int)$json['counter'];

        return $this;
    }
}
