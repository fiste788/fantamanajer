<?php

namespace App\Service;

use App\Model\Entity\Credential;
use App\Model\Entity\User;
use App\Model\Table\CredentialsTable;
use Cake\Datasource\ModelAwareTrait;
use CBOR\Decoder;
use CBOR\OtherObject\OtherObjectManager;
use CBOR\Tag\TagObjectManager;
use Cose\Algorithms;
use Cose\Algorithm\Manager;
use Cose\Algorithm\Signature\ECDSA;
use Cose\Algorithm\Signature\EdDSA;
use Cose\Algorithm\Signature\RSA;
use Psr\Http\Message\ServerRequestInterface;
use Webauthn\AttestationStatement\AndroidKeyAttestationStatementSupport;
use Webauthn\AttestationStatement\AndroidSafetyNetAttestationStatementSupport;
use Webauthn\AttestationStatement\AttestationObjectLoader;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\FidoU2FAttestationStatementSupport;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AttestationStatement\PackedAttestationStatementSupport;
use Webauthn\AttestationStatement\TPMAttestationStatementSupport;
use Webauthn\AttestedCredentialData;
use Webauthn\AuthenticationExtensions\AuthenticationExtension;
use Webauthn\AuthenticationExtensions\AuthenticationExtensionsClientInputs;
use Webauthn\AuthenticationExtensions\ExtensionOutputCheckerHandler;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\CredentialRepository as RepositoryInterface;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\TokenBinding\TokenBindingNotSupportedHandler;

/**
 * Credentials Repo
 *
 * @property CredentialsTable $Credentials
 */
class CredentialService implements RepositoryInterface
{
    use ModelAwareTrait;

    /**
     * Costructor
     */
    public function __construct()
    {
        $this->loadModel('Credentials');
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId credentialId
     * @return Credential
     */
    private function findByCredentialId($credentialId)
    {
        return $this->Credentials->find(['credential_id' => $credentialId])->first();
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId credentialId
     * @return bool
     */
    public function has(string $credentialId): bool
    {
        return $this->Credentials->exists(['credential_id' => $credentialId]);
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId id
     * @return AttestedCredentialData
     */
    public function get(string $credentialId): AttestedCredentialData
    {
        $credential = $this->findByCredentialId($credentialId);

        return $credential->attested_credential_data;
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId id
     * @return string
     */
    public function getUserHandleFor(string $credentialId): string
    {
        $credential = $this->Credentials->find()->contain(['Users'])->where(['credential_id' => $credentialId])->first();

        return $credential->user->email;
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId id
     * @return int
     */
    public function getCounterFor(string $credentialId): int
    {
        return $this->Credentials->find(['credential_id' => $credentialId])->first()->counter;
    }

    /**
     * Undocumented function
     *
     * @param string $credentialId id
     * @param int $newCounter new value
     * @return void
     */
    public function updateCounterFor(string $credentialId, int $newCounter): void
    {
        $credential = $this->Credentials->find(['credential_id' => $credentialId])->first();
        $credential->counter = $newCounter;
        $this->Credentials->save($credential);
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\User $user User
     * @return PublicKeyCredentialUserEntity
     */
    public function createUserEntity(User $user)
    {
        // User Entity
        return new PublicKeyCredentialUserEntity(
            $user->email,
            $user->getIdentifier(),
            $user->name . ' ' . $user->surname,
            null
        );
    }

    /**
     * Undocumented function
     *
     * @return AuthenticationExtensionsClientInputs
     */
    private function getExtensions()
    {
        $extensions = new AuthenticationExtensionsClientInputs();
        $extensions->add(new AuthenticationExtension('loc', true));

        return $extensions;
    }

    /**
     * Undocumented function
     *
     * @return PublicKeyCredentialRpEntity
     */
    private function createRpEntity()
    {
        return new PublicKeyCredentialRpEntity(
            'FantaManajer', //Name
            //'fantamanajer.it', //ID
            'localhost',
            null //Icon
        );
    }

    /**
     * Undocumented function
     *
     * @return Decoder
     */
    private function createDecoder()
    {
        // Create a CBOR Decoder object
        $otherObjectManager = new OtherObjectManager();
        $tagObjectManager = new TagObjectManager();

        return new Decoder($tagObjectManager, $otherObjectManager);
    }

    /**
     * Undocumented function
     *
     * @return Manager
     */
    private function createAlgorithManager()
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
     * @param Decoded $decoder Decoder
     * @return PublicKeyCredentialLoader
     */
    private function createLoader(Decoder $decoder)
    {
        // Cose Algorithm Manager
        $coseAlgorithmManager = $this->createAlgorithManager();

        // Attestation Statement Support Manager
        $attestationStatementSupportManager = new AttestationStatementSupportManager();
        $attestationStatementSupportManager->add(new NoneAttestationStatementSupport());
        $attestationStatementSupportManager->add(new FidoU2FAttestationStatementSupport($decoder));
        $attestationStatementSupportManager->add(new PackedAttestationStatementSupport($decoder, $coseAlgorithmManager));

        // Attestation Object Loader
        $attestationObjectLoader = new AttestationObjectLoader($attestationStatementSupportManager, $decoder);

        // Public Key Credential Loader
        return new PublicKeyCredentialLoader($attestationObjectLoader, $decoder);
    }

    /**
     * Undocumented function
     *
     * @param ServerRequestInterface $request Request
     * @return PublicKeyCredentialRequestOptions
     */
    public function publicKeyRequest(ServerRequestInterface $request)
    {
        // List of registered PublicKeyCredentialDescriptor classes associated to the user
        $credentials = $this->Credentials->find()->where(['user_id' => $this->Authentication->getIdentity()->getIdentifier()]);
        $registeredPublicKeyCredentialDescriptors = $credentials->extract('public_key')->toList();

        // Public Key Credential Request Options
        $publicKeyCredentialRequestOptions = new PublicKeyCredentialRequestOptions(
            random_bytes(32),
            60000,
            'localhost',
            $registeredPublicKeyCredentialDescriptors,
            PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            $this->getExtensions()
        );
        $request->getSession()->start();
        $request->getSession()->write("User.PublicKey", $publicKeyCredentialRequestOptions);

        return $publicKeyCredentialRequestOptions;
    }

    /**
     * Undocumented function
     *
     * @param ServerRequestInterface $request Request
     * @return PublicKeyCredentialCreationOptions
     */
    public function publicKeyCreation(ServerRequestInterface $request)
    {
        $rpEntity = $this->createRpEntity();
        $userEntity = $this->createUserEntity($request->getAttribute('identity'));

        // Public Key Credential Parameters
        $publicKeyCredentialParametersList = [
            new PublicKeyCredentialParameters('public-key', Algorithms::COSE_ALGORITHM_ES256),
            new PublicKeyCredentialParameters('public-key', Algorithms::COSE_ALGORITHM_RS256)
        ];

        // Devices to exclude
        $excludedPublicKeyDescriptors = [
            new PublicKeyCredentialDescriptor(PublicKeyCredentialDescriptor::CREDENTIAL_TYPE_PUBLIC_KEY, 'ABCDEFGH'),
        ];

        // Authenticator Selection Criteria (we used default values)
        $authenticatorSelectionCriteria = new AuthenticatorSelectionCriteria();

        $publicKeyCredentialCreationOptions = new PublicKeyCredentialCreationOptions(
            $rpEntity,
            $userEntity,
            random_bytes(32),
            $publicKeyCredentialParametersList,
            20000,
            $excludedPublicKeyDescriptors,
            $authenticatorSelectionCriteria,
            PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_NONE,
            $this->getExtensions()
        );

        $session = $request->getSession();
        $session->start();
        $session->write("User.PublicKey", json_encode($publicKeyCredentialCreationOptions));

        return $publicKeyCredentialCreationOptions;
    }

    /**
     * Undocumented function
     *
     * @param ServerRequestInterface $request Request
     * @return bool
     */
    public function login(ServerRequestInterface $request)
    {
        $publicKey = $request->getSession()->consume("User.PublicKey");
        $json = json_decode($publicKey, true);
        $publicKeyCredentialRequestOptions = PublicKeyCredentialRequestOptions::createFromJson($json);

        $decoder = $this->createDecoder();
        $publicKeyCredentialLoader = $this->createLoader($decoder);

        // Authenticator Assertion Response Validator
        $authenticatorAssertionResponseValidator = new AuthenticatorAssertionResponseValidator(
            $this,
            $decoder,
            new TokenBindingNotSupportedHandler(),
            new ExtensionOutputCheckerHandler()
        );

        try {
            // Load the data
            $publicKeyCredential = $publicKeyCredentialLoader->load(json_encode($request->getData()));
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
                $request,
                null // User handle
            );

            return true;
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * Save the credential
     *
     * @param  ServerRequestInterface $request Request
     * @return bool
     */
    public function register(ServerRequestInterface $request)
    {
        $publicKey = $request->getSession()->consume("User.PublicKey");
        $json = json_decode($publicKey, true);
        $publicKeyCredentialCreationOptions = PublicKeyCredentialCreationOptions::createFromJson($json);

        $decoder = $this->createDecoder();

        // Attestation Statement Support Manager
        $attestationStatementSupportManager = new AttestationStatementSupportManager();
        $attestationStatementSupportManager->add(new NoneAttestationStatementSupport());
        $attestationStatementSupportManager->add(new FidoU2FAttestationStatementSupport($decoder));
        // $attestationStatementSupportManager->add(new AndroidSafetyNetAttestationStatementSupport($httpClient, 'GOOGLE_SAFETYNET_API_KEY'));
        $attestationStatementSupportManager->add(new AndroidKeyAttestationStatementSupport($decoder));
        $attestationStatementSupportManager->add(new TPMAttestationStatementSupport());
        $attestationStatementSupportManager->add(new PackedAttestationStatementSupport($decoder, $this->createAlgorithManager()));

        // Attestation Object Loader
        $attestationObjectLoader = new AttestationObjectLoader($attestationStatementSupportManager, $decoder);

        // Public Key Credential Loader
        $publicKeyCredentialLoader = new PublicKeyCredentialLoader($attestationObjectLoader, $decoder);

        $authenticatorAttestationResponseValidator = new AuthenticatorAttestationResponseValidator(
            $attestationStatementSupportManager,
            $this,
            new TokenBindingNotSupportedHandler(),
            new ExtensionOutputCheckerHandler()
        );

        try {
            // Load the data
            $publicKeyCredential = $publicKeyCredentialLoader->load(json_encode($request->getData()));
            $response = $publicKeyCredential->getResponse();

            // Check if the response is an Authenticator Attestation Response
            if (!$response instanceof AuthenticatorAttestationResponse) {
                throw new \RuntimeException('Not an authenticator attestation response');
            }

            // Check the response against the request
            $check = $authenticatorAttestationResponseValidator->check($response, $publicKeyCredentialCreationOptions, $request);
            // Everything is OK here. You can get the PublicKeyCredentialDescriptor.
            $publicKeyCredentialDescriptor = $publicKeyCredential->getPublicKeyCredentialDescriptor();

            // Normally this condition should be true. Just make sure you received the credential data
            $attestedCredentialData = null;
            if ($response->getAttestationObject()->getAuthData()->hasAttestedCredentialData()) {
                $attestedCredentialData = $response->getAttestationObject()->getAuthData()->getAttestedCredentialData();
            }

            $credential = $this->Credentials->newEntity();
            $credential->user_id = $request->getAttribute('identity')->getIdentifier();
            $credential->attested_credential_data = $attestedCredentialData;
            $credential->credential_id = $publicKeyCredentialDescriptor;
            $this->Credentials->save($credential);

            return true;
        } catch (\Throwable $exception) {
            throw $exception;

            return false;
        }
    }
}
