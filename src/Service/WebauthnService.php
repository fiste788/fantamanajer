<?php
declare(strict_types=1);

namespace App\Service;

use AllowDynamicProperties;
use App\Model\Entity\PublicKeyCredentialSource as EntityPublicKeyCredentialSource;
use App\Model\Entity\User;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Cose\Algorithm\Manager;
use Cose\Algorithm\Signature\ECDSA;
use Cose\Algorithm\Signature\EdDSA;
use Cose\Algorithm\Signature\RSA;
use Cose\Algorithms;
use Jose\Component\Core\Util\Base64UrlSafe;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Webauthn\AttestationStatement\AndroidKeyAttestationStatementSupport;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\FidoU2FAttestationStatementSupport;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AttestationStatement\PackedAttestationStatementSupport;
use Webauthn\AttestationStatement\TPMAttestationStatementSupport;
use Webauthn\AuthenticationExtensions\AuthenticationExtension;
use Webauthn\AuthenticationExtensions\AuthenticationExtensions;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialUserEntity;
use WhichBrowser\Parser;

/**
 * Credentials Repo
 */
#[AllowDynamicProperties]
class WebauthnService
{
    use LocatorAwareTrait;
    use ServiceAwareTrait;

    protected AuthenticatorAttestationResponseValidator $authenticatorAttestationResponseValidator;

    protected AuthenticatorAssertionResponseValidator $authenticatorAssertionResponseValidator;

    protected PublicKeyCredentialSourceRepositoryService $PublicKeyCredentialSourceRepository;

    public SerializerInterface $serializer;

    /**
     * Costructor
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $this->loadService('PublicKeyCredentialSourceRepository');
        $attestationStatementSupportManager = $this->createStatementSupportManager();

        $factory = new WebauthnSerializerFactory($attestationStatementSupportManager);
        $this->serializer = $factory->create();

        $csmFactory = new CeremonyStepManagerFactory();

        $creationCSM = $csmFactory->creationCeremony();
        $requestCSM = $csmFactory->requestCeremony();

        $this->authenticatorAttestationResponseValidator = new AuthenticatorAttestationResponseValidator(
            $creationCSM,
        );

        // Authenticator Assertion Response Validator
        $this->authenticatorAssertionResponseValidator = new AuthenticatorAssertionResponseValidator(
            $requestCSM,
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
            (string)$user->email,
            (string)$user->uuid,
            ($user->name ?? '') . ' ' . ($user->surname ?? ''),
            null,
        );
    }

    /**
     * Undocumented function
     *
     * @return \Webauthn\AuthenticationExtensions\AuthenticationExtensions
     */
    private function getExtensions(): AuthenticationExtensions
    {
        $extensions = new AuthenticationExtensions([
            new AuthenticationExtension('loc', true),
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
            (string)Configure::read('Webauthn.name', 'FantaManajer'),
            //Name
            (string)Configure::read('Webauthn.id', 'fantamanajer.it'),
            //ID
            (string)Configure::read('Webauthn.icon'), //Icon
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
        $attestationStatementSupportManager->add(AndroidKeyAttestationStatementSupport::create());
        $attestationStatementSupportManager->add(TPMAttestationStatementSupport::create());
        $attestationStatementSupportManager->add(
            PackedAttestationStatementSupport::create(
                $coseAlgorithmManager,
            ),
        );

        return $attestationStatementSupportManager;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return mixed
     * @throws \RuntimeException
     */
    public function signinRequest(ServerRequestInterface $request): mixed
    {
        // List of registered PublicKeyCredentialDescriptor classes associated to the user
        $params = $request->getQueryParams();
        $allowedCredentials = [];
        if (array_key_exists('email', $params)) {
            /** @var \App\Model\Entity\User|null $user */
            $user = $this->fetchTable('Users')->find()->where(['email' => $params['email']])->first();
            if ($user != null) {
                $credentialUser = $user->toCredentialUserEntity();

                $credentials = $this->PublicKeyCredentialSourceRepository->findAllForUserEntity(
                    $this->serializer,
                    $credentialUser,
                );
                $allowedCredentials = $this->credentialsToDescriptors($credentials);
                $request->getSession()->write('User.Handle', $credentialUser->id);
            }
        }
        // Public Key Credential Request Options
        $publicKeyCredentialRequestOptions =
            PublicKeyCredentialRequestOptions::create(
                random_bytes(32),
                (string)Configure::read('Webauthn.id', 'fantamanajer.it'),
                $allowedCredentials,
                PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_REQUIRED,
                60_000,
                $this->getExtensions(),
            );

        $jsonObject = $this->serializer->serialize(
            $publicKeyCredentialRequestOptions,
            'json',
            [ // Optional
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                JsonEncode::OPTIONS => JSON_THROW_ON_ERROR,
            ],
        );
        $request->getSession()->start();
        $request->getSession()->write('User.PublicKey', $jsonObject);

        return json_decode($jsonObject);
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return bool
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \TypeError
     */
    public function signinResponse(ServerRequestInterface $request): bool
    {
        $publicKey = (string)$request->getSession()->consume('User.PublicKey');
        /** @var string|null $handle */
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
     * @throws \TypeError
     */
    public function signin(
        string $publicKey,
        ServerRequestInterface $request,
        ?string $userHandle = null,
    ): PublicKeyCredentialSource {
        $publicKeyCredentialRequestOptions = $this->serializer->deserialize(
            $publicKey,
            PublicKeyCredentialRequestOptions::class,
            'json',
        );

        // Load the data
        $body = $request->getBody()->__toString();
        /** @var \Webauthn\PublicKeyCredential $publicKeyCredential */
        $publicKeyCredential = $this->serializer->deserialize(
            $body,
            PublicKeyCredential::class,
            'json',
        );
        $authenticatorAssertionResponse = $publicKeyCredential->response;

        // Check if the response is an Authenticator Assertion Response
        if (!$authenticatorAssertionResponse instanceof AuthenticatorAssertionResponse) {
            throw new RuntimeException('Not an authenticator assertion response');
        }

        $publicKeyCredentialSourcesTable = $this->fetchTable('PublicKeyCredentialSources');
        /** @var \App\Model\Entity\PublicKeyCredentialSource $credential */
        $credential = $publicKeyCredentialSourcesTable->find()
            ->where(['public_key_credential_id' => Base64UrlSafe::encodeUnpadded($publicKeyCredential->rawId)])
            ->first();

        // Check the response against the attestation request
        $response = $this->authenticatorAssertionResponseValidator->check(
            $credential->toCredentialSource($this->serializer),
            $authenticatorAssertionResponse,
            $publicKeyCredentialRequestOptions,
            (string)Configure::read('Webauthn.id', 'fantamanajer.it'),
            $userHandle,
        );

        $credential->last_seen_at = new DateTime();
        $this->updateUserAgent($credential, $request->getHeader('User-Agent')[0]);
        $publicKeyCredentialSourcesTable->save($credential);

        return $response;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function registerRequest(ServerRequestInterface $request): mixed
    {
        $rpEntity = $this->createRpEntity();

        /** @var \App\Model\Entity\User $user */
        $user = $request->getAttribute('identity');
        $userEntity = $user->toCredentialUserEntity();

        /** @var \App\Model\Table\PublicKeyCredentialSourcesTable $publicKeyCredentialSourcesTable */
        $publicKeyCredentialSourcesTable = $this->fetchTable('PublicKeyCredentialSources');
        $credential = $publicKeyCredentialSourcesTable->newEmptyEntity();
        $credential = $this->PublicKeyCredentialSourceRepository->findAllForUserEntity($this->serializer, $userEntity);
        $excludeCredentials = $this->credentialsToDescriptors($credential);

        // Public Key Credential Parameters
        $publicKeyCredentialParametersList = [
            new PublicKeyCredentialParameters('public-key', Algorithms::COSE_ALGORITHM_ES256),
            new PublicKeyCredentialParameters('public-key', Algorithms::COSE_ALGORITHM_RS256),
        ];

        // Authenticator Selection Criteria (we used default values)
        $authenticatorSelectionCriteria = AuthenticatorSelectionCriteria::create(
            AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_PLATFORM,
            AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_PREFERRED,
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
            60_000,
        );

        $jsonObject = $this->serializer->serialize(
            $publicKeyCredentialCreationOptions,
            'json',
            [ // Optional
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                JsonEncode::OPTIONS => JSON_THROW_ON_ERROR,
            ],
        );

        $session = $request->getSession();
        $session->start();
        $session->write('User.PublicKey', $jsonObject);

        return json_decode($jsonObject);
    }

    /**
     * Save the credential
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return \App\Model\Entity\PublicKeyCredentialSource|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @throws \TypeError
     * @throws \RangeException
     */
    public function registerResponse(ServerRequestInterface $request): ?EntityPublicKeyCredentialSource
    {
        $publicKey = (string)$request->getSession()->consume('User.PublicKey');
        $publicKeyCredentialCreationOptions = $this->serializer->deserialize(
            $publicKey,
            PublicKeyCredentialCreationOptions::class,
            'json',
        );

        // Load the data
        $body = $request->getBody()->__toString();
        $publicKeyCredential = $this->serializer->deserialize(
            $body,
            PublicKeyCredential::class,
            'json',
        );

        $authenticatorAttestationResponse = $publicKeyCredential->response;

        // Check if the response is an Authenticator Attestation Response
        if (!$authenticatorAttestationResponse instanceof AuthenticatorAttestationResponse) {
            throw new RuntimeException('Not an authenticator attestation response');
        }

        // Check the response against the request
        $credentialSource = $this->authenticatorAttestationResponseValidator->check(
            $authenticatorAttestationResponse,
            $publicKeyCredentialCreationOptions,
            (string)Configure::read('Webauthn.id', 'fantamanajer.it'),
        );

        /** @var \App\Model\Table\PublicKeyCredentialSourcesTable $publicKeyCredentialSourcesTable */
        $publicKeyCredentialSourcesTable = $this->fetchTable('PublicKeyCredentialSources');
        $credential = $publicKeyCredentialSourcesTable->newEmptyEntity();
        $credential->fromCredentialSource($this->serializer, $credentialSource);
        $credential->id = Uuid::v4()->toRfc4122();
        $this->updateUserAgent($credential, $request->getHeaderLine('User-Agent'));

        return $publicKeyCredentialSourcesTable->save($credential) ?: null;
    }

    /**
     * Update useragent
     *
     * @param \App\Model\Entity\PublicKeyCredentialSource $credential Credential
     * @param string $userAgent User-agent
     * @return void
     */
    private function updateUserAgent(EntityPublicKeyCredentialSource $credential, string $userAgent): void
    {
        $credential->user_agent = $userAgent;
        $parsed = new Parser($credential->user_agent);
        $credential->name = $parsed->toString();
    }
}
