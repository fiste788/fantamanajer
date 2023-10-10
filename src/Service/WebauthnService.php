<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\PublicKeyCredentialSource as EntityPublicKeyCredentialSource;
use App\Model\Entity\User;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Cose\Algorithm\Manager;
use Cose\Algorithm\Signature\ECDSA;
use Cose\Algorithm\Signature\EdDSA;
use Cose\Algorithm\Signature\RSA;
use Cose\Algorithms;
use GuzzleHttp\Psr7\HttpFactory;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Symfony\Component\Uid\Uuid;
use Webauthn\AttestationStatement\AndroidKeyAttestationStatementSupport;
use Webauthn\AttestationStatement\AndroidSafetyNetAttestationStatementSupport;
use Webauthn\AttestationStatement\AttestationObjectLoader;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\FidoU2FAttestationStatementSupport;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AttestationStatement\PackedAttestationStatementSupport;
use Webauthn\AttestationStatement\TPMAttestationStatementSupport;
use Webauthn\AuthenticationExtensions\AuthenticationExtension;
use Webauthn\AuthenticationExtensions\AuthenticationExtensionsClientInputs;
use Webauthn\AuthenticationExtensions\ExtensionOutputCheckerHandler;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialUserEntity;
use WhichBrowser\Parser;

/**
 * Credentials Repo
 *
 * @property \App\Service\PublicKeyCredentialSourceRepositoryService $PublicKeyCredentialSourceRepository
 */
#[\AllowDynamicProperties]
class WebauthnService
{
    use LocatorAwareTrait;
    use ServiceAwareTrait;

    protected PublicKeyCredentialLoader $publicKeyCredentialLoader;

    protected AuthenticatorAttestationResponseValidator $authenticatorAttestationResponseValidator;

    protected AuthenticatorAssertionResponseValidator $authenticatorAssertionResponseValidator;

    /**
     * Costructor
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->loadService('PublicKeyCredentialSourceRepository');
        $attestationStatementSupportManager = $this->createStatementSupportManager();

        // Attestation Object Loader
        $attestationObjectLoader = new AttestationObjectLoader($attestationStatementSupportManager);

        // Public Key Credential Loader
        $this->publicKeyCredentialLoader = new PublicKeyCredentialLoader($attestationObjectLoader);

        $this->authenticatorAttestationResponseValidator = new AuthenticatorAttestationResponseValidator(
            $attestationStatementSupportManager,
            $this->PublicKeyCredentialSourceRepository,
            null,
            new ExtensionOutputCheckerHandler()
        );

        // Authenticator Assertion Response Validator
        $this->authenticatorAssertionResponseValidator = new AuthenticatorAssertionResponseValidator(
            $this->PublicKeyCredentialSourceRepository,
            null,
            new ExtensionOutputCheckerHandler(),
            $this->createAlgorithManager()
        );

    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\User $user User
     * @return \Webauthn\PublicKeyCredentialUserEntity
     */
    public function createUserEntity(User $user): PublicKeyCredentialUserEntity
    {
        // User Entity
        return PublicKeyCredentialUserEntity::create(
            (string) $user->email,
            (string) $user->uuid,
            ($user->name ?? '') . ' ' . ($user->surname ?? ''),
            null
        );
    }

    /**
     * Undocumented function
     *
     * @return \Webauthn\AuthenticationExtensions\AuthenticationExtensionsClientInputs
     */
    private function getExtensions(): AuthenticationExtensionsClientInputs
    {
        $extensions = new AuthenticationExtensionsClientInputs([
            new AuthenticationExtension('loc', true)
        ]);

        return $extensions;
    }

    /**
     * Undocumented function
     *
     * @return \Webauthn\PublicKeyCredentialRpEntity
     */
    private function createRpEntity(): PublicKeyCredentialRpEntity
    {
        return PublicKeyCredentialRpEntity::create(
            (string) Configure::read('Webauthn.name', 'FantaManajer'),
            //Name
            (string) Configure::read('Webauthn.id', 'fantamanajer.it'),
            //ID
            (string) Configure::read('Webauthn.icon') //Icon
        );
    }

    /**
     * Undocumented function
     *
     * @param array<\Webauthn\PublicKeyCredentialSource> $credentials credentials
     * @return array<\Webauthn\PublicKeyCredentialDescriptor>
     */
    private function credentialsToDescriptors(array $credentials): array
    {
        /** @var array<\Webauthn\PublicKeyCredentialDescriptor> $credentials */
        $credentials = Hash::map($credentials, '{*}', function (PublicKeyCredentialSource $value) {
            return $value->getPublicKeyCredentialDescriptor();
        });

        return $credentials;
    }

    /**
     * Undocumented function
     *
     * @return \Cose\Algorithm\Manager
     */
    private function createAlgorithManager(): Manager
    {
        // Cose Algorithm Manager
        $coseAlgorithmManager = new Manager();
        $coseAlgorithmManager->add(new ECDSA\ES256());
        $coseAlgorithmManager->add(new ECDSA\ES512());
        $coseAlgorithmManager->add(new EdDSA\EdDSA());
        $coseAlgorithmManager->add(new RSA\RS1());
        $coseAlgorithmManager->add(new RSA\RS256());
        $coseAlgorithmManager->add(new RSA\RS512());

        return $coseAlgorithmManager;
    }

    /**
     * Undocumented function
     *
     * @return \Webauthn\AttestationStatement\AttestationStatementSupportManager
     * @throws \InvalidArgumentException
     */
    private function createStatementSupportManager(): AttestationStatementSupportManager
    {
        $coseAlgorithmManager = $this->createAlgorithManager();

        $attestationStatementSupportManager = AttestationStatementSupportManager::create();
        $attestationStatementSupportManager->add(NoneAttestationStatementSupport::create());
        $attestationStatementSupportManager->add(FidoU2FAttestationStatementSupport::create());
        $attestationStatementSupportManager->add(
            AndroidSafetyNetAttestationStatementSupport::create()
                ->enableApiVerification(
                    new Client(),
                    (string) Configure::read('Webauthn.safetyNetKey'),
                    new HttpFactory()
                )
        );
        $attestationStatementSupportManager->add(AndroidKeyAttestationStatementSupport::create());
        $attestationStatementSupportManager->add(TPMAttestationStatementSupport::create());
        $attestationStatementSupportManager->add(
            PackedAttestationStatementSupport::create(
                $coseAlgorithmManager
            )
        );

        return $attestationStatementSupportManager;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return \Webauthn\PublicKeyCredentialRequestOptions|null
     * @throws \RuntimeException
     */
    public function signinRequest(ServerRequestInterface $request): ?PublicKeyCredentialRequestOptions
    {
        // List of registered PublicKeyCredentialDescriptor classes associated to the user
        $params = $request->getQueryParams();
        $allowedCredentials = [];
        if (array_key_exists('email', $params)) {
            /** @var \App\Model\Entity\User|null $user */
            $user = $this->fetchTable('Users')->find()->where(['email' => $params['email']])->first();
            if ($user != null) {
                $credentialUser = $user->toCredentialUserEntity();
                $credentials = $this->PublicKeyCredentialSourceRepository->findAllForUserEntity($credentialUser);
                $allowedCredentials = $this->credentialsToDescriptors($credentials);
                $request->getSession()->write('User.Handle', $credentialUser->id);
            }
        }
        // Public Key Credential Request Options
        $publicKeyCredentialRequestOptions =
            PublicKeyCredentialRequestOptions::create(
                random_bytes(32),
                (string) Configure::read('Webauthn.id', 'fantamanajer.it'),
                $allowedCredentials,
                PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_REQUIRED,
                60_000,
                $this->getExtensions()
            );
        $request->getSession()->start();
        $request->getSession()->write(
            'User.PublicKey',
            json_encode(
                $publicKeyCredentialRequestOptions,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );

        return $publicKeyCredentialRequestOptions;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return bool
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function signinResponse(ServerRequestInterface $request): bool
    {
        $publicKey = (string) $request->getSession()->consume('User.PublicKey');
        $handle = $request->getSession()->consume('User.Handle');

        $response = $this->signin($publicKey, $request, $handle);

        return $response != null;
    }

    /**
     * Undocumented function
     *
     * @param string $publicKey Public key
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @param string $userHandle User Handle
     * @return \Webauthn\PublicKeyCredentialSource
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function signin(
        string $publicKey,
        ServerRequestInterface $request,
        string $userHandle = null
    ): PublicKeyCredentialSource {
        $publicKeyCredentialRequestOptions = PublicKeyCredentialRequestOptions::createFromString($publicKey);

        // Load the data
        /** @var array<string, mixed> $body */
        $body = $request->getParsedBody();
        $publicKeyCredential = $this->publicKeyCredentialLoader->loadArray($body);
        $authenticatorAssertionResponse = $publicKeyCredential->response;

        // Check if the response is an Authenticator Assertion Response
        if (!$authenticatorAssertionResponse instanceof AuthenticatorAssertionResponse) {
            throw new RuntimeException('Not an authenticator assertion response');
        }

        // Check the response against the attestation request
        $response = $this->authenticatorAssertionResponseValidator->check(
            $publicKeyCredential->rawId,
            $authenticatorAssertionResponse,
            $publicKeyCredentialRequestOptions,
            $request,
            $userHandle,
            ['localhost']
        );

        $publicKeyCredentialSourcesTable = $this->fetchTable('PublicKeyCredentialSources');
        /** @var \App\Model\Entity\PublicKeyCredentialSource $publicKey */
        $publicKey = $publicKeyCredentialSourcesTable->find()->where(['public_key_credential_id' => $response->publicKeyCredentialId])->first();
        $publicKey->last_seen_at = new FrozenTime();
        $publicKeyCredentialSourcesTable->save($publicKey);
        return $response;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return \Webauthn\PublicKeyCredentialCreationOptions
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function registerRequest(ServerRequestInterface $request): PublicKeyCredentialCreationOptions
    {
        $rpEntity = $this->createRpEntity();

        /** @var \App\Model\Entity\User $user */
        $user = $request->getAttribute('identity');
        $userEntity = $user->toCredentialUserEntity();

        $credential = $this->PublicKeyCredentialSourceRepository->findAllForUserEntity($userEntity);
        $excludeCredentials = $this->credentialsToDescriptors($credential);

        // Public Key Credential Parameters
        $publicKeyCredentialParametersList = [
            new PublicKeyCredentialParameters('public-key', Algorithms::COSE_ALGORITHM_ES256),
            new PublicKeyCredentialParameters('public-key', Algorithms::COSE_ALGORITHM_RS256),
        ];

        // Authenticator Selection Criteria (we used default values)
        $authenticatorSelectionCriteria = AuthenticatorSelectionCriteria::create(
            AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_CROSS_PLATFORM,
            AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_PREFERRED,
            true
        );
        //$authenticatorSelectionCriteria = new AuthenticatorSelectionCriteria();

        $publicKeyCredentialCreationOptions = PublicKeyCredentialCreationOptions::create(
            $rpEntity,
            $userEntity,
            random_bytes(32),
            $publicKeyCredentialParametersList,
            $authenticatorSelectionCriteria,
            PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_NONE,
            $excludeCredentials,
            60_000
        );

        $session = $request->getSession();
        $session->start();
        $session->write(
            'User.PublicKey',
            json_encode(
                $publicKeyCredentialCreationOptions,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );

        return $publicKeyCredentialCreationOptions;
    }

    /**
     * Save the credential
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return \App\Model\Entity\PublicKeyCredentialSource|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function registerResponse(ServerRequestInterface $request): ?EntityPublicKeyCredentialSource
    {
        $publicKey = (string) $request->getSession()->consume('User.PublicKey');
        $publicKeyCredentialCreationOptions = PublicKeyCredentialCreationOptions::createFromString($publicKey);

        // Load the data
        /** @var array<string, mixed> $body */
        $body = $request->getParsedBody();
        $publicKeyCredential = $this->publicKeyCredentialLoader->loadArray($body);
        $authenticatorAttestationResponse = $publicKeyCredential->response;

        // Check if the response is an Authenticator Attestation Response
        if (!$authenticatorAttestationResponse instanceof AuthenticatorAttestationResponse) {
            throw new RuntimeException('Not an authenticator attestation response');
        }

        // Check the response against the request
        $credentialSource = $this->authenticatorAttestationResponseValidator->check(
            $authenticatorAttestationResponse,
            $publicKeyCredentialCreationOptions,
            $request,
            ['localhost']
        );

        /** @var \App\Model\Table\PublicKeyCredentialSourcesTable $publicKeyCredentialSourcesTable */
        $publicKeyCredentialSourcesTable = $this->fetchTable('PublicKeyCredentialSources');
        $credential = $publicKeyCredentialSourcesTable->newEmptyEntity();
        $credential->fromCredentialSource($credentialSource);
        $credential->id = Uuid::v4()->toRfc4122();
        $credential->user_agent = $request->getHeader('User-Agent')[0];
        $parsed = new Parser($credential->user_agent);
        $credential->name = $parsed->toString();

        return $publicKeyCredentialSourcesTable->save($credential) ?: null;
    }
}