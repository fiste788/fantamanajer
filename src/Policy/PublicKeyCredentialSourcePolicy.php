<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\PublicKeyCredentialSource;
use Authorization\IdentityInterface;

class PublicKeyCredentialSourcePolicy
{
    /**
     * Can edit
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\PublicKeyCredentialSource $pkcs Entity
     * @return bool
     */
    public function canEdit(IdentityInterface $user, PublicKeyCredentialSource $pkcs)
    {
        return $user->uuid == $pkcs->user_handle;
    }

    /**
     * Can delete
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\PublicKeyCredentialSource $pkcs Entity
     * @return bool
     */
    public function canDelete(IdentityInterface $user, PublicKeyCredentialSource $pkcs)
    {
        return $user->uuid == $pkcs->user_handle;
    }

    /**
     * Can add
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\PublicKeyCredentialSource $pkcs Entity
     * @return bool
     */
    public function canAdd(IdentityInterface $user, PublicKeyCredentialSource $pkcs)
    {
        return $user->uuid == $pkcs->user_handle;
    }

    /**
     * Can index
     *
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\PublicKeyCredentialSource $pkcs Entity
     * @return bool
     */
    public function canIndex(IdentityInterface $user, PublicKeyCredentialSource $pkcs)
    {
        return $user->uuid == $pkcs->user_handle;
    }
}
