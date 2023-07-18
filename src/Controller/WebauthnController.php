<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\UserService;
use App\Service\WebauthnService;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Event\EventInterface;

/**
 * @property \App\Service\UserService $User
 */
class WebauthnController extends AppController
{
    use ServiceAwareTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('User');
    }

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['publicKeyRequest', 'login']);
    }

    /**
     * Undocumented function
     *
     * @param \App\Service\WebauthnService $webauthn Webauthn service
     * @return void
     * @throws \RuntimeException
     */
    public function publicKeyRequest(WebauthnService $webauthn): void
    {
        $publicKeyCredentialRequestOptions = $webauthn->assertionRequest($this->request);

        $this->set([
            'success' => true,
            'data' => ['publicKey' => $publicKeyCredentialRequestOptions],
            '_serialize' => ['success', 'data'],
        ]);
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
    public function publicKeyCreation(WebauthnService $webauthn): void
    {
        $publicKeyCredentialCreationOptions = $webauthn->creationRequest($this->request);

        $this->set([
            'success' => true,
            'data' => ['publicKey' => $publicKeyCredentialCreationOptions],
            '_serialize' => ['success', 'data'],
        ]);
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
        $this->set('_serialize', ['success', 'data']);
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
    public function register(WebauthnService $webauthn): void
    {
        $token = $webauthn->creationResponse($this->request);
        $this->set([
            'success' => true,
            'data' => $token,
            '_serialize' => ['success', 'data'],
        ]);
    }
}
