<?php
declare(strict_types=1);

namespace App\Controller;

use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\EventInterface;

/**
 * @property \App\Service\WebauthnService $Webauthn
 * @property \App\Service\UserService $User
 */
class WebauthnController extends AppController
{
    use ServiceAwareTrait;
    use ModelAwareTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('User');
        $this->loadService('Webauthn');
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
     * @return void
     * @throws \RuntimeException
     */
    public function publicKeyRequest()
    {
        $publicKeyCredentialRequestOptions = $this->Webauthn->assertionRequest($this->request);

        $this->set([
            'success' => true,
            'data' => ['publicKey' => $publicKeyCredentialRequestOptions],
            '_serialize' => ['success', 'data'],
        ]);
    }

    /**
     * Get Webauthn public key
     *
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function publicKeyCreation()
    {
        $publicKeyCredentialCreationOptions = $this->Webauthn->attestationRequest($this->request);

        $this->set([
            'success' => true,
            'data' => ['publicKey' => $publicKeyCredentialCreationOptions],
            '_serialize' => ['success', 'data'],
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function login()
    {
        $result = $this->Authentication->getResult();
        if ($result != null && $result->isValid()) {
            /** @var \App\Model\Entity\User $user */
            $user = $this->Authentication->getIdentity();
            $days = $this->request->getData('remember_me', false) ? 365 : 7;
            $this->set('data', [
                'token' => $this->User->getToken((string)$user->id, $days),
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
     * @return void
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function register()
    {
        $token = $this->Webauthn->attestationResponse($this->request);
        $this->set([
            'success' => true,
            'data' => $token,
            '_serialize' => ['success', 'data'],
        ]);
    }
}
