<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use Webauthn\PublicKeyCredentialSource as WebauthnPublicKeyCredentialSource;
use Webauthn\TrustPath\TrustPath;

/**
 * PublicKeyCredentialSource Entity
 *
 * @property string $id
 * @property string $public_key_credential_id
 * @property string $type
 * @property array $transports
 * @property string $attestation_type
 * @property TrustPath $trust_path
 * @property string $aaguid
 * @property string $credential_public_key
 * @property string $user_handle
 * @property int $counter
 * @property \Cake\I18n\FrozenTime $created_at
 * @property string|null $name
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
     * @var array
     */
    protected $_accessible = [
        'public_key_credential_id' => true,
        'type' => true,
        'transports' => true,
        'attestation_type' => true,
        'trust_path' => true,
        'aaguid' => true,
        'credential_public_key' => true,
        'user_handle' => true,
        'counter' => true,
        'created_at' => true,
        'name' => true,
        'user' => true
    ];

    /**
     * Undocumented function
     *
     * @return WebauthnPublicKeyCredentialSource
     */
    public function toCredentialSource(): WebauthnPublicKeyCredentialSource
    {
        return new WebauthnPublicKeyCredentialSource($this->public_key_credential_id, $this->type, $this->transports, $this->attestation_type, $this->trust_path, $this->aaguid, $this->credential_public_key, $this->user_handle, $this->counter);
    }

    /**
     * Undocumented function
     *
     * @param WebauthnPublicKeyCredentialSource $credentialSource arg
     * @return void
     */
    public function fromCredentialSource(WebauthnPublicKeyCredentialSource $credentialSource)
    {
        $this->public_key_credential_id = $credentialSource->getPublicKeyCredentialId();
        $this->type = $credentialSource->getType();
        $this->transports = $credentialSource->getTransports();
        $this->attestation_type = $credentialSource->getAttestationType();
        $this->trust_path = $credentialSource->getTrustPath();
        $this->aaguid = $credentialSource->getAaguid();
        $this->credential_public_key = $credentialSource->getCredentialPublicKey();
        $this->user_handle = $credentialSource->getUserHandle();
        $this->counter = $credentialSource->getCounter();
    }
}
