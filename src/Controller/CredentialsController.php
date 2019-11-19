<?php
declare(strict_types=1);

namespace App\Controller;

use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Event\EventInterface;

/**
 * @property \App\Service\CredentialService $Credential
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
            '_jsonOptions' => (JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
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
            '_jsonOptions' => (JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
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
        $check = $this->Credential->assertionResponse($this->request);
        $this->set([
            'success' => $check,
            'data' => $check,
            '_serialize' => ['success', 'data'],
        ]);
    }

    /**
     * Save the credential
     *
     * @return void
     */
    public function register()
    {
        $check = $this->Credential->attestationResponse($this->request);
        $this->set([
            'success' => $check,
            'data' => $check,
            '_serialize' => ['success', 'data'],
        ]);
    }
}
