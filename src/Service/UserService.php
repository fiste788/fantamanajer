<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Utility\Security;
use Firebase\JWT\JWT;

class UserService
{
    /**
     * Get auth token
     *
     * @param string $subject The id
     * @param int $days The days token is valid
     * @return string
     */
    public function getToken(string $subject, int $days = 7): string
    {
        return JWT::encode(
            [
                'sub' => $subject,
                'exp' => time() + ($days * 24 * 60 * 60),
            ],
            Security::getSalt()
        );
    }
}
