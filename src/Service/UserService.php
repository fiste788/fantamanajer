<?php

namespace App\Service;

use Cake\Utility\Security;
use Firebase\JWT\JWT;

class UserService
{
    public function getToken($subject, $days = 7)
    {
        return JWT::encode(
            [
                    'sub' => $subject,
                    'exp' => time() + ($days * 24 * 60 * 60)
                ],
            Security::getSalt()
        );
    }
}
