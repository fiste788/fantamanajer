<?php

namespace App\Controller;

use App\Service\CredentialService;
use App\Service\UserService;
use App\Stream\ActivityManager;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Log\Log;
use Cake\Network\Exception\UnauthorizedException;
use CBOR\Decoder;
use CBOR\OtherObject\OtherObjectManager;
use CBOR\Tag\TagObjectManager;
use Cose\Algorithms;
use Cose\Algorithm\Manager;
use Cose\Algorithm\Signature\ECDSA;
use Cose\Algorithm\Signature\EdDSA;
use Cose\Algorithm\Signature\RSA;
use Webauthn\AttestationStatement\AttestationObjectLoader;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\FidoU2FAttestationStatementSupport;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AttestationStatement\PackedAttestationStatementSupport;
use Webauthn\AuthenticationExtensions\AuthenticationExtension;
use Webauthn\AuthenticationExtensions\AuthenticationExtensionsClientInputs;
use Webauthn\AuthenticationExtensions\ExtensionOutputCheckerHandler;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\TokenBinding\TokenBindingNotSupportedHandler;
use Webauthn\PublicKeyCredentialRequestOptions;
use function Safe\json_encode;
use Cake\Event\EventInterface;

/**
 * @property UserService $User
 * @property CredentialService $Credential
 */
class UsersController extends AppController
{
    use ServiceAwareTrait;

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('User');
        $this->loadService('Credential');
    }

    /**
     * Before filter
     *
     * @param Event $event Event
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Authentication->allowUnauthenticated(['login', 'publicKey', 'storeKey']);
    }

    /**
     * Return current user
     *
     * @return void
     */
    public function current()
    {
        $this->set([
            'success' => true,
            'data' => $this->Authentication->getIdentity(),
            '_serialize' => ['success', 'data']
        ]);
    }

    /**
     * Get login Token
     *
     * @throws UnauthorizedException
     */
    public function login()
    {
        if ($this->Authentication->getResult()->isValid()) {
            $user = $this->Authentication->getIdentity();
            $days = $this->request->getData('remember_me', false) ? 365 : 7;
            $this->set(
                [
                    'success' => true,
                    'data' => [
                        'token' => $this->User->getToken($user->id, $days),
                        'user' => $user->getOriginalData()
                    ],
                    '_serialize' => ['success', 'data']
                ]
            );
        } else {
            throw new \Exception($this->Authentication->getResult()->getStatus(), 401);
        }
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        $this->Authentication->logout();
        $this->set(
            [
                'success' => true,
                'data' => true,
                '_serialize' => ['success', 'data']
            ]
        );
    }

    /**
     * Get activity stream
     *
     * @return void
     */
    public function stream()
    {
        $userId = $this->request->getParam('user_id');
        if (!$this->Authentication->getIdentity()->id == $userId) {
            throw new ForbiddenException();
        }

        $page = $this->request->getQuery('page', 1);
        $rowsForPage = 10;
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('user', $userId, false, $offset, $rowsForPage);
        $this->set([
            'stream' => $stream,
            '_serialize' => 'stream'
        ]);
    }

    public function getPublicKey()
    {

        // Extensions
        $extensions = new AuthenticationExtensionsClientInputs();
        $extensions->add(new AuthenticationExtension('loc', true));

        $credentials = $this->Credential->Credentials->find()->where(['user_id' => $this->Authentication->getIdentity()]);
        // List of registered PublicKeyCredentialDescriptor classes associated to the user
        $registeredPublicKeyCredentialDescriptors = $credentials->extract('public_key')->toList();

        // Public Key Credential Request Options
        $publicKeyCredentialRequestOptions = new PublicKeyCredentialRequestOptions(
            random_bytes(32),
            60000,
            'localhost',
            $registeredPublicKeyCredentialDescriptors,
            PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            $extensions
        );
        $this->getRequest()->getSession()->start();
        $this->getRequest()->getSession()->write("User.PublicKey", $publicKeyCredentialRequestOptions);

        $this->set([
            'success' => true,
            'data' => $publicKeyCredentialRequestOptions,
            '_serialize' => ['success', 'data']
        ]);
    }

    /**
     * Get Webauthn public key
     *
     * @return void
     */
    public function publicKey()
    {
        // RP Entity
        $rpEntity = new PublicKeyCredentialRpEntity(
            'FantaManajer', //Name
            //'fantamanajer.it', //ID
            'localhost',
            null //Icon
        );

        // User Entity
        $userEntity = new PublicKeyCredentialUserEntity(
            $this->request->getData('username'), //Name
            $this->request->getData('username'), //ID
            'Mighty Mike', //Display name
            null //Icon
        );

        // Challenge
        $challenge = random_bytes(32);

        // Public Key Credential Parameters
        $publicKeyCredentialParametersList = [
            new PublicKeyCredentialParameters('public-key', Algorithms::COSE_ALGORITHM_ES256),
            new PublicKeyCredentialParameters('public-key', Algorithms::COSE_ALGORITHM_RS256)
        ];

        // Timeout
        $timeout = 20000;

        // Devices to exclude
        $excludedPublicKeyDescriptors = [
            new PublicKeyCredentialDescriptor(PublicKeyCredentialDescriptor::CREDENTIAL_TYPE_PUBLIC_KEY, 'ABCDEFGH'),
        ];

        // Authenticator Selection Criteria (we used default values)
        $authenticatorSelectionCriteria = new AuthenticatorSelectionCriteria();

        // Extensions
        $extensions = new AuthenticationExtensionsClientInputs();
        $extensions->add(new AuthenticationExtension('loc', true));

        $publicKeyCredentialCreationOptions = new PublicKeyCredentialCreationOptions(
            $rpEntity,
            $userEntity,
            $challenge,
            $publicKeyCredentialParametersList,
            $timeout,
            $excludedPublicKeyDescriptors,
            $authenticatorSelectionCriteria,
            PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_NONE,
            $extensions
        );
        /*$credential = $this->Credential->Credentials->newEntity();
        $credential->user_id = $this->Authentication->getIdentity()->id;
        $credential->challenge = base64_encode($publicKeyCredentialCreationOptions->getChallenge());
        $credential->public_key = json_encode($publicKeyCredentialCreationOptions);
        $this->Credential->Credentials->save($credential);*/

        $session = $this->getRequest()->getSession();
        $session->start();
        $session->write("User.PublicKey", json_encode($publicKeyCredentialCreationOptions));
        $this->set([
            'success' => true,
            'data' => $publicKeyCredentialCreationOptions,
            '_serialize' => ['success', 'data']
        ]);
    }

    public function credentialLogin()
    {
        // Retrieve the Options passed to the device
        $publicKeyCredentialRequestOptions = $this->getRequest()->getSession()->consume("User.PublicKey");

        // Cose Algorithm Manager
        $coseAlgorithmManager = new Manager();
        $coseAlgorithmManager->add(new ECDSA\ES256());
        $coseAlgorithmManager->add(new ECDSA\ES512());
        $coseAlgorithmManager->add(new EdDSA\EdDSA());
        $coseAlgorithmManager->add(new RSA\RS1());
        $coseAlgorithmManager->add(new RSA\RS256());
        $coseAlgorithmManager->add(new RSA\RS512());

        // Retrieve de data sent by the device
        $data = $this->request->getData();

        // Create a CBOR Decoder object
        $otherObjectManager = new OtherObjectManager();
        $tagObjectManager = new TagObjectManager();
        $decoder = new Decoder($tagObjectManager, $otherObjectManager);

        // Attestation Statement Support Manager
        $attestationStatementSupportManager = new AttestationStatementSupportManager();
        $attestationStatementSupportManager->add(new NoneAttestationStatementSupport());
        $attestationStatementSupportManager->add(new FidoU2FAttestationStatementSupport($decoder));
        $attestationStatementSupportManager->add(new PackedAttestationStatementSupport($decoder, $coseAlgorithmManager));

        // Attestation Object Loader
        $attestationObjectLoader = new AttestationObjectLoader($attestationStatementSupportManager, $decoder);

        // Public Key Credential Loader
        $publicKeyCredentialLoader = new PublicKeyCredentialLoader($attestationObjectLoader, $decoder);

        // Credential Repository
        $credentialRepository = $this->request->getData('credential');

        // The token binding handler
        $tokenBindnigHandler = new TokenBindingNotSupportedHandler();

        // Extension Output Checker Handler
        $extensionOutputCheckerHandler = new ExtensionOutputCheckerHandler();

        // Authenticator Assertion Response Validator
        $authenticatorAssertionResponseValidator = new AuthenticatorAssertionResponseValidator(
            $credentialRepository,
            $decoder,
            $tokenBindnigHandler,
            $extensionOutputCheckerHandler
        );

        try {
            // We init the PSR7 Request object
            $psr7Request = $this->getRequest();

            // Load the data
            $publicKeyCredential = $publicKeyCredentialLoader->load($data);
            $response = $publicKeyCredential->getResponse();

            // Check if the response is an Authenticator Assertion Response
            if (!$response instanceof AuthenticatorAssertionResponse) {
                throw new \RuntimeException('Not an authenticator assertion response');
            }

            // Check the response against the attestation request
            $authenticatorAssertionResponseValidator->check(
                $publicKeyCredential->getRawId(),
                $publicKeyCredential->getResponse(),
                $publicKeyCredentialRequestOptions,
                $psr7Request,
                null // User handle
            );
        } catch (\Throwable $throwable) { }
    }

    /**
     * Save the credential
     *
     * @return void
     */
    public function storeKey()
    {
        $session = $this->request->getSession();
        $session->start();
        Log::error(print_r($session, true));
        //$credential = $this->Credential->Credentials->find()->where(['challenge' => $this->request->getData('challenge')])->first();
        //$publicKey = $credential->public_key;
        $publicKey = $this->getRequest()->getSession()->consume("User.PublicKey");
        $json = json_decode($publicKey, true);
        Log::error(print_r($json, true));
        $publicKeyCredentialCreationOptions = PublicKeyCredentialCreationOptions::createFromJson($json);

        // Retrieve de data sent by the device
        $data = $this->request->getData('credential');
        Log::error('aaaaa' . print_r($data, true));

        // Cose Algorithm Manager
        $coseAlgorithmManager = new Manager();
        $coseAlgorithmManager->add(new ECDSA\ES256());
        $coseAlgorithmManager->add(new ECDSA\ES512());
        $coseAlgorithmManager->add(new EdDSA\EdDSA());
        $coseAlgorithmManager->add(new RSA\RS1());
        $coseAlgorithmManager->add(new RSA\RS256());
        $coseAlgorithmManager->add(new RSA\RS512());

        // Create a CBOR Decoder object
        $otherObjectManager = new OtherObjectManager();
        $tagObjectManager = new TagObjectManager();
        $decoder = new Decoder($tagObjectManager, $otherObjectManager);

        // The token binding handler
        $tokenBindnigHandler = new TokenBindingNotSupportedHandler();

        // Attestation Statement Support Manager
        $attestationStatementSupportManager = new AttestationStatementSupportManager();
        $attestationStatementSupportManager->add(new NoneAttestationStatementSupport());
        $attestationStatementSupportManager->add(new FidoU2FAttestationStatementSupport($decoder));
        $attestationStatementSupportManager->add(new PackedAttestationStatementSupport($decoder, $coseAlgorithmManager));

        // Attestation Object Loader
        $attestationObjectLoader = new AttestationObjectLoader($attestationStatementSupportManager, $decoder);

        // Public Key Credential Loader
        $publicKeyCredentialLoader = new PublicKeyCredentialLoader($attestationObjectLoader, $decoder);

        // Credential Repository
        $credentialRepository = $this->Credential;

        // Extension Output Checker Handler
        $extensionOutputCheckerHandler = new ExtensionOutputCheckerHandler();

        // Authenticator Attestation Response Validator
        $authenticatorAttestationResponseValidator = new AuthenticatorAttestationResponseValidator(
            $attestationStatementSupportManager,
            $credentialRepository,
            $tokenBindnigHandler,
            $extensionOutputCheckerHandler
        );

        try {
            // Load the data
            $publicKeyCredential = $publicKeyCredentialLoader->load(json_encode($data));
            $response = $publicKeyCredential->getResponse();

            // Check if the response is an Authenticator Attestation Response
            if (!$response instanceof AuthenticatorAttestationResponse) {
                throw new \RuntimeException('Not an authenticator attestation response');
            }

            // Check the response against the request
            $check = $authenticatorAttestationResponseValidator->check($response, $publicKeyCredentialCreationOptions, $this->request);
            // Everything is OK here. You can get the PublicKeyCredentialDescriptor.
            $publicKeyCredentialDescriptor = $publicKeyCredential->getPublicKeyCredentialDescriptor();

            // Normally this condition should be true. Just make sure you received the credential data
            $attestedCredentialData = null;
            if ($response->getAttestationObject()->getAuthData()->hasAttestedCredentialData()) {
                $attestedCredentialData = $response->getAttestationObject()->getAuthData()->getAttestedCredentialData();
            }

            $credential = $this->Credential->Credentials->newEntity();
            $credential->user_id = $this->Authentication->getIdentity()->getIdentifier();
            $credential->attested_credential_data = $attestedCredentialData;
            $credential->credential_id = $publicKeyCredentialDescriptor;
            $this->Credential->Credentials->save($credential);
            Log::error('check ' . $check);
            $this->set([
                'success' => true,
                'data' => $response->getAttestationObject()->getAuthData()->isUserVerified(),
                '_serialize' => ['success', 'data']
            ]);
        } catch (\Throwable $exception) {
            Log::error("excep " . $exception);
            $this->set([
                'success' => false,
                'data' => $exception->getMessage(),
                '_serialize' => ['success', 'data']
            ]);
        }
    }
}
