<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class UsersController extends AppController
{
    /**
     * Initialize
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add', 'token']);
    }

    /**
     * Get login Token
     *
     * @throws UnauthorizedException
     */
    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Invalid username or password');
        }
        $days = $this->request->getData('remember_me', false) ? 365 : 7;

        $this->set(
            [
            'success' => true,
            'data' => [
                'token' => JWT::encode(
                    [
                    'sub' => $user['id'],
                    'exp' => time() + ($days * 24 * 60 * 60)
                    ],
                    Security::salt()
                ),
                'user' => $user
            ],
            '_serialize' => ['success', 'data']
            ]
        );
    }

    /**
     *
     * @param int $id
     * @return UnauthorizedException
     */
    public function edit($id)
    {
        if ($this->Auth->user("id") != $id) {
            return new UnauthorizedException('Access denied');
        }

        return $this->Crud->execute();
    }
}
