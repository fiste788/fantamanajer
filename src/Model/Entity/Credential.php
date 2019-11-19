<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Credential Entity
 *
 * @property int $id
 * @property string $credential_id
 * @property \Webauthn\AttestedCredentialData $attested_credential_data
 * @property \Webauthn\PublicKeyCredentialDescriptor $public_key
 * @property int $counter
 * @property string $user_agent
 * @property string $name
 * @property int $user_id
 *
 * @property \App\Model\Entity\User $user
 */
class Credential extends Entity
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
        'counter' => true,
        'user_id' => true,
        'user' => true,
    ];
}
