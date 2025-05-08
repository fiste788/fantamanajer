<?php
declare(strict_types=1);

namespace App\Authentication\Authenticator;

use Authentication\Authenticator\AbstractAuthenticator;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Authentication\Identifier\AbstractIdentifier;
use Authentication\UrlChecker\UrlCheckerTrait;
use Override;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Undocumented class
 */
class WebauthnAuthenticator extends AbstractAuthenticator
{
    use UrlCheckerTrait;

    /**
     * Default config for this object.
     * - `fields` The fields to use to identify a user by.
     * - `loginUrl` Login URL or an array of URLs.
     * - `urlChecker` Url checker config.
     *
     * @var array<array-key, mixed>
     */
    protected array $_defaultConfig = [
        'loginUrl' => null,
        'urlChecker' => 'Authentication.Default',
        'fields' => [
            AbstractIdentifier::CREDENTIAL_USERNAME => 'username',
            AbstractIdentifier::CREDENTIAL_PASSWORD => 'password',
        ],
    ];

    /**
     * Prepares the error object for a login URL error
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request that contains login information.
     * @return \Authentication\Authenticator\Result
     * @throws \InvalidArgumentException
     */
    protected function _buildLoginUrlErrorResult(ServerRequestInterface $request): Result
    {
        /** @var array<string> $config */
        $config = $this->getConfig('loginUrl');
        $errors = [
            sprintf(
                'Login URL `%s` did not match `%s`.',
                (string)$request->getUri(),
                implode('` or `', $config),
            ),
        ];

        return new Result(null, Result::FAILURE_OTHER, $errors);
    }

    /**
     * Authenticates the identity contained in a request. Will use the `config.userModel`, and `config.fields`
     * to find POST data that is used to find a matching record in the `config.userModel`. Will return false if
     * there is no post data, either username or password is missing, or if the scope conditions have not been met.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request that contains login information.
     * @return \Authentication\Authenticator\Result
     * @throws \InvalidArgumentException
     */
    #[Override]
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        if (!$this->_checkUrl($request) || $request->getMethod() != 'POST') {
            return $this->_buildLoginUrlErrorResult($request);
        }

        $user = $this->_identifier->identify([
            'request' => $request,
            'publicKey' => $this->getPublicKey($request),
            'userHandle' => $this->getUserHandle($request),
        ]);

        if ($user == null) {
            return new Result(null, Result::FAILURE_IDENTITY_NOT_FOUND, $this->_identifier->getErrors());
        }

        return new Result($user, Result::SUCCESS);
    }

    /**
     * Undocumented function
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return string|null
     */
    public function getPublicKey(ServerRequestInterface $request): ?string
    {
        /** @var \Cake\Http\ServerRequest $cakeRequest */
        $cakeRequest = $request;

        /** @var string|null $key */
        $key = $cakeRequest->getSession()->consume('User.PublicKey');

        return $key != null ? $key : null;
    }

    /**
     * Undocumented function
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return string|null
     */
    public function getUserHandle(ServerRequestInterface $request): ?string
    {
        /** @var \Cake\Http\ServerRequest $cakeRequest */
        $cakeRequest = $request;

        /** @var string|null $handle */
        $handle = $cakeRequest->getSession()->consume('User.Handle');

        return $handle != null ? $handle : null;
    }
}
