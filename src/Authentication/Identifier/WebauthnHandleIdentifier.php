<?php
declare(strict_types=1);

namespace App\Authentication\Identifier;

use Authentication\Identifier\AbstractIdentifier;
use Authentication\Identifier\Resolver\ResolverAwareTrait;
use Authentication\Identifier\Resolver\ResolverInterface;
use Burzum\Cake\Service\ServiceAwareTrait;

class WebauthnHandleIdentifier extends AbstractIdentifier
{
    use ResolverAwareTrait;
    use ServiceAwareTrait;

    /**
     * Default configuration.
     * - `fields` The fields to use to identify a user by:
     *   - `username`: one or many username fields.
     * - `resolver` The resolver implementation to use.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [
            self::CREDENTIAL_USERNAME => 'uuid',
        ],
        'resolver' => 'Authentication.Orm',
    ];

    /**
     * @inheritdoc
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function identify(array $data)
    {
        if (!isset($data['publicKey']) || !isset($data['userHandle'])) {
            return null;
        }

        /** @var \Psr\Http\Message\ServerRequestInterface $request */
        $request = $data['request'];
        $publicKey = (string)$data['publicKey'];
        $userHandle = (string)$data['userHandle'];

        /** @var \App\Service\CredentialService $credentialService */
        $credentialService = $this->getServiceLocator()->load('Credential');

        $result = $credentialService->login($publicKey, $request, $userHandle);

        return $this->_findIdentity($result->getUserHandle());
    }

    /**
     * Find a user record using the username/identifier provided.
     *
     * @param string $identifier The username/identifier.
     * @return \ArrayAccess|array|null
     * @throws \RuntimeException
     */
    protected function _findIdentity(string $identifier)
    {
        /** @var string[] $fields */
        $fields = $this->getConfig('fields.' . self::CREDENTIAL_USERNAME);
        $conditions = [];
        foreach ((array)$fields as $field) {
            $conditions[$field] = $identifier;
        }

        return $this->getResolver()->find($conditions, ResolverInterface::TYPE_OR);
    }
}
