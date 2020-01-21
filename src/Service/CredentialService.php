<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\PublicKeyCredentialSource as EntityPublicKeyCredentialSource;
use App\Model\Entity\User;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Client;
use Cake\Utility\Hash;
use Cose\Algorithm\Manager;
use Cose\Algorithm\Signature\ECDSA;
use Cose\Algorithm\Signature\EdDSA;
use Cose\Algorithm\Signature\RSA;
use Cose\Algorithms;
use Psr\Http\Message\ServerRequestInterface;
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
use Webauthn\TokenBinding\TokenBindingNotSupportedHandler;
use WhichBrowser\Parser;

/**
 * Credentials Repo
 *
 * @property \App\Service\CredentialRepositoryService $CredentialRepository
 * @property \App\Model\Table\PublicKeyCredentialSourcesTable $PublicKeyCredentialSources
 * @property \App\Model\Table\UsersTable $Users
 */
class CredentialService
{
    use ModelAwareTrait;
    use ServiceAwareTrait;

    /**
     * Costructor
     */
    public function __construct()
    {
        $this->loadModel('Users');
        $this->loadModel('PublicKeyCredentialSources');
        $this->loadService('CredentialRepository');
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
        return new PublicKeyCredentialUserEntity(
            (string)$user->id,
            (string)$user->uuid,
            $user->name . ' ' . $user->surname,
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
        $extensions = new AuthenticationExtensionsClientInputs();
        $extensions->add(new AuthenticationExtension('loc', true));

        return $extensions;
    }

    /**
     * Undocumented function
     *
     * @return \Webauthn\PublicKeyCredentialRpEntity
     */
    private function createRpEntity(): PublicKeyCredentialRpEntity
    {
        return new PublicKeyCredentialRpEntity(
            'FantaManajer', //Name
            (string)Configure::read('Webauthn.id', 'fantamanajer.it'), //ID
            null //Icon
        );
    }

    /**
     * Undocumented function
     *
     * @param \Webauthn\PublicKeyCredentialSource[] $credentials credentials
     * @return \Webauthn\PublicKeyCredentialDescriptor[]
     * @psalm-suppress MixedReturnTypeCoercion
     */
    private function credentialsToDescriptors(array $credentials): array
    {
        return Hash::map($credentials, '{*}', function (PublicKeyCredentialSource $value) {
            return $value->getPublicKeyCredentialDescriptor();
        });
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
     */
    private function createStatementSupportManager(): AttestationStatementSupportManager
    {
        $coseAlgorithmManager = $this->createAlgorithManager();

        $attestationStatementSupportManager = new AttestationStatementSupportManager();
        $attestationStatementSupportManager->add(new NoneAttestationStatementSupport());
        $attestationStatementSupportManager->add(new FidoU2FAttestationStatementSupport());
        $attestationStatementSupportManager->add(new AndroidSafetyNetAttestationStatementSupport(
            new Client(),
            (string)Configure::read('Webauthn.safetyNetKey')
        ));
        $attestationStatementSupportManager->add(new AndroidKeyAttestationStatementSupport());
        $attestationStatementSupportManager->add(new TPMAttestationStatementSupport());
        $attestationStatementSupportManager->add(new PackedAttestationStatementSupport(
            $coseAlgorithmManager
        ));

        return $attestationStatementSupportManager;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return \Webauthn\PublicKeyCredentialRequestOptions|null
     */
    public function assertionRequest(ServerRequestInterface $request): ?PublicKeyCredentialRequestOptions
    {
        // List of registered PublicKeyCredentialDescriptor classes associated to the user
        $params = $request->getQueryParams();
        /** @var \App\Model\Entity\User|null $user */
        $user = $this->Users->find()->where(['email' => $params['email']])->first();
        if ($user != null) {
            $credentialUser = $user->toCredentialUserEntity();
            $credentials = $this->CredentialRepository->findAllForUserEntity($credentialUser);
            $registeredPublicKeyCredentialDescriptors = $this->credentialsToDescriptors($credentials);

            // Public Key Credential Request Options
            $publicKeyCredentialRequestOptions = new PublicKeyCredentialRequestOptions(
                random_bytes(32),
                60000,
                (string)Configure::read('Webauthn.id', 'fantamanajer.it'),
                $registeredPublicKeyCredentialDescriptors,
                AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
                $this->getExtensions()
            );
            $request->getSession()->start();
            $request->getSession()->write("User.Handle", $credentialUser->getId());
            $request->getSession()->write("User.PublicKey", json_encode(
                $publicKeyCredentialRequestOptions,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ));

            return $publicKeyCredentialRequestOptions;
        } else {
            return null;
        }
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return bool
     */
    public function assertionResponse(ServerRequestInterface $request): bool
    {
        $publicKey = (string)$request->getSession()->consume("User.PublicKey");
        $handle = (string)$request->getSession()->consume('User.Handle');

        $response = $this->login($publicKey, $request, $handle);

        return $response != null;
    }

    /**
     * Undocumented function
     *
     * @param string $publicKey Public key
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @param string $userHandle User Handle
     * @return \Webauthn\PublicKeyCredentialSource
     */
    public function login(
        string $publicKey,
        ServerRequestInterface $request,
        string $userHandle
    ): PublicKeyCredentialSource {
        /** @var \Webauthn\PublicKeyCredentialRequestOptions $publicKeyCredentialRequestOptions */
        $publicKeyCredentialRequestOptions = PublicKeyCredentialRequestOptions::createFromString($publicKey);

        $attestationStatementSupportManager = $this->createStatementSupportManager();
        // Attestation Object Loader
        $attestationObjectLoader = new AttestationObjectLoader($attestationStatementSupportManager);

        // Public Key Credential Loader
        $publicKeyCredentialLoader = new PublicKeyCredentialLoader($attestationObjectLoader);

        // Authenticator Assertion Response Validator
        $authenticatorAssertionResponseValidator = new AuthenticatorAssertionResponseValidator(
            $this->CredentialRepository,
            new TokenBindingNotSupportedHandler(),
            new ExtensionOutputCheckerHandler(),
            $this->createAlgorithManager()
        );

        try {
            // Load the data
            /** @var array<string, mixed> $body */
            $body = $request->getParsedBody();
            $publicKeyCredential = $publicKeyCredentialLoader->loadArray($body);
            $response = $publicKeyCredential->getResponse();

            // Check if the response is an Authenticator Assertion Response
            if (!$response instanceof AuthenticatorAssertionResponse) {
                throw new \RuntimeException('Not an authenticator assertion response');
            }

            // Check the response against the attestation request
            return $authenticatorAssertionResponseValidator->check(
                $publicKeyCredential->getRawId(),
                $response,
                $publicKeyCredentialRequestOptions,
                $request,
                $userHandle
            );
        } catch (\Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return \Webauthn\PublicKeyCredentialCreationOptions
     */
    public function attestationRequest(ServerRequestInterface $request): PublicKeyCredentialCreationOptions
    {
        $rpEntity = $this->createRpEntity();

        /** @var \App\Model\Entity\User $user */
        $user = $request->getAttribute('identity');
        if ($user->uuid == null) {
            $user->uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
            $this->Users->save($user);
        }
        $userEntity = $user->toCredentialUserEntity();

        $credential = $this->CredentialRepository->findAllForUserEntity($userEntity);
        $excludeCredentials = $this->credentialsToDescriptors($credential);

        // Public Key Credential Parameters
        $publicKeyCredentialParametersList = [
            new PublicKeyCredentialParameters('public-key', Algorithms::COSE_ALGORITHM_ES256),
            new PublicKeyCredentialParameters('public-key', Algorithms::COSE_ALGORITHM_RS256),
        ];

        // Authenticator Selection Criteria (we used default values)
        $authenticatorSelectionCriteria = new AuthenticatorSelectionCriteria(
            AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_NO_PREFERENCE,
            false,
            AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED
        );
        //$authenticatorSelectionCriteria = new AuthenticatorSelectionCriteria();

        $publicKeyCredentialCreationOptions = new PublicKeyCredentialCreationOptions(
            $rpEntity,
            $userEntity,
            random_bytes(32),
            $publicKeyCredentialParametersList,
            60000,
            $excludeCredentials,
            $authenticatorSelectionCriteria,
            PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_NONE,
            $this->getExtensions()
        );

        $session = $request->getSession();
        $session->start();
        $session->write("User.PublicKey", json_encode(
            $publicKeyCredentialCreationOptions,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        ));

        return $publicKeyCredentialCreationOptions;
    }

    /**
     * Save the credential
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return \App\Model\Entity\PublicKeyCredentialSource|null
     */
    public function attestationResponse(ServerRequestInterface $request): ?EntityPublicKeyCredentialSource
    {
        $publicKey = (string)$request->getSession()->consume("User.PublicKey");

        /** @var \Webauthn\PublicKeyCredentialCreationOptions $publicKeyCredentialCreationOptions */
        $publicKeyCredentialCreationOptions = PublicKeyCredentialCreationOptions::createFromString($publicKey);

        // Attestation Statement Support Manager
        $attestationStatementSupportManager = $this->createStatementSupportManager();

        // Attestation Object Loader
        $attestationObjectLoader = new AttestationObjectLoader($attestationStatementSupportManager);

        // Public Key Credential Loader
        $publicKeyCredentialLoader = new PublicKeyCredentialLoader($attestationObjectLoader);

        $authenticatorAttestationResponseValidator = new AuthenticatorAttestationResponseValidator(
            $attestationStatementSupportManager,
            $this->CredentialRepository,
            new TokenBindingNotSupportedHandler(),
            new ExtensionOutputCheckerHandler()
        );

        try {
            // Load the data
            /** @var array<string, mixed> $body */
            $body = $request->getParsedBody();
            $publicKeyCredential = $publicKeyCredentialLoader->loadArray($body);
            $response = $publicKeyCredential->getResponse();

            // Check if the response is an Authenticator Attestation Response
            if (!$response instanceof AuthenticatorAttestationResponse) {
                throw new \RuntimeException('Not an authenticator attestation response');
            }

            // Check the response against the request
            $credentialSource = $authenticatorAttestationResponseValidator->check(
                $response,
                $publicKeyCredentialCreationOptions,
                $request
            );

            $credential = $this->PublicKeyCredentialSources->newEmptyEntity();
            $credential->fromCredentialSource($credentialSource);
            $credential->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
            $credential->user_agent = $request->getHeader('User-Agent')[0];
            $parsed = new Parser($credential->user_agent);
            $credential->name = $parsed->toString();

            return $this->PublicKeyCredentialSources->save($credential) ?: null;
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
