<?php

namespace MKaverin\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessTokenInterface;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use MKaverin\OAuth2\Client\Provider\Exception\DonationAlertsIdentityProviderException;
use Psr\Http\Message\ResponseInterface;

class DonationAlerts extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public string $domain = 'https://www.donationalerts.com';

    public string $apiDomain = 'https://www.donationalerts.com/api/v1';

    /**
     * @inheritDoc
     */
    public function getBaseAuthorizationUrl(): string
    {
        return $this->domain . '/oauth/authorize';
    }

    /**
     * @inheritDoc
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->domain . '/oauth/token';
    }

    /**
     * @inheritDoc
     */
    public function getResourceOwnerDetailsUrl(AccessTokenInterface $token): string
    {
        return $this->apiDomain . '/user/oauth';
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultScopes(): array
    {
        return ['oauth-user-show'];
    }

    /**
     * @inheritDoc
     */
    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    /**
     * @inheritDoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw DonationAlertsIdentityProviderException::clientException($response, $data);
        } elseif (isset($data['error'])) {
            throw DonationAlertsIdentityProviderException::oauthException($response, $data);
        }
    }

    /**
     * @inheritDoc
     */
    protected function createResourceOwner(array $response, AccessTokenInterface $token): DonationAlertsResourceOwner
    {
        return new DonationAlertsResourceOwner($response);
    }
}
