<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\UserService;
use App\Service\WebauthnService;
use Cake\Event\EventInterface;

class WebauthnController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['signinRequest', 'login']);
    }

    /**
     * Undocumented function
     *
     * @param \App\Service\WebauthnService $webauthn Webauthn service
     * @return void
     * @throws \RuntimeException
     */
    public function signinRequest(WebauthnService $webauthn): void
    {
        $publicKeyCredentialRequestOptions = $webauthn->signinRequest($this->request);

        $this->set([
            'success' => true,
            'data' => ['publicKey' => $publicKeyCredentialRequestOptions],
        ]);

        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }

    /**
     * Get Webauthn public key
     *
     * @param \App\Service\WebauthnService $webauthn Webauthn service
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function registerRequest(WebauthnService $webauthn): void
    {
        $publicKeyCredentialCreationOptions = $webauthn->registerRequest($this->request);

        $this->set([
            'success' => true,
            'data' => ['publicKey' => $publicKeyCredentialCreationOptions, 'mediation' => 'conditional'],
        ]);

        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }

    /**
     * Undocumented function
     *
     * @param \App\Service\UserService $userService User service
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function login(UserService $userService): void
    {
        $result = $this->Authentication->getResult();
        if ($result != null && $result->isValid()) {
            /** @var \App\Model\Entity\User $user */
            $user = $this->Authentication->getIdentity();
            $days = $this->request->getData('remember_me', false) ? 365 : 7;
            $this->set('data', [
                'token' => $userService->getToken((string)$user->id, $days),
                'user' => $user->getOriginalData(),
            ]);
        } else {
            $this->response = $this->response->withStatus(401);
            $this->set('data', [
                'message' => $result != null ? $result->getStatus() : 'Authentication error',
            ]);
        }
        $this->set('success', $result != null && $result->isValid());

        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }

    /**
     * Save the credential
     *
     * @param \App\Service\WebauthnService $webauthn Webauthn service
     * @return void
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function registerResponse(WebauthnService $webauthn): void
    {
        $token = $webauthn->registerResponse($this->request);
        $this->set([
            'success' => true,
            'data' => $token,
        ]);

        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }
}
