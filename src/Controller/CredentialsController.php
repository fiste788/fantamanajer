<?php
declare(strict_types=1);

namespace App\Controller;

use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Event\EventInterface;

/**
 * @property \App\Service\CredentialService $Credential
 * @property \App\Service\UserService $User
 * @property \Cake\ORM\Table $Credentials
 */
class CredentialsController extends AppController
{
    use ServiceAwareTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('User');
        $this->loadService('Credential');
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
     */
    public function publicKeyRequest()
    {
        $publicKeyCredentialRequestOptions = $this->Credential->assertionRequest($this->request);

        $this->set([
            'success' => true,
            'data' => $publicKeyCredentialRequestOptions,
            '_serialize' => ['success', 'data'],
        ]);
    }

    /**
     * Get Webauthn public key
     *
     * @return void
     */
    public function publicKeyCreation()
    {
        $publicKeyCredentialCreationOptions = $this->Credential->attestationRequest($this->request);

        $this->set([
            'success' => true,
            'data' => $publicKeyCredentialCreationOptions,
            '_serialize' => ['success', 'data'],
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
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
     */
    public function register()
    {
        $token = $this->Credential->attestationResponse($this->request);
        $this->set([
            'success' => true,
            'data' => $token,
            '_serialize' => ['success', 'data'],
        ]);
    }
}
