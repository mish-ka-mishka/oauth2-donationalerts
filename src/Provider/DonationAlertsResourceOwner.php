<?php

namespace MKaverin\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class DonationAlertsResourceOwner implements ResourceOwnerInterface
{
    /**
     * Raw response
     */
    protected array $responseData;

    public function __construct(array $response = [])
    {
        $this->responseData = $response['data'] ?? [];
    }

    /**
     * The unique and unchangeable user identifier
     */
    public function getId(): int
    {
        return $this->responseData['id'];
    }

    /**
     * The unique user name
     */
    public function getCode(): string
    {
        return $this->responseData['code'];
    }

    /**
     * The unique displayed user name
     */
    public function getName(): ?string
    {
        return $this->responseData['name'];
    }

    /**
     * The URL to the personalized graphical illustration
     */
    public function getAvatar(): ?string
    {
        return $this->responseData['avatar'];
    }

    /**
     * The email address
     */
    public function getEmail(): ?string
    {
        return $this->responseData['email'];
    }

    /**
     * Centrifugo connection token
     */
    public function getSocketConnectionToken(): ?string
    {
        return $this->responseData['socket_connection_token'];
    }

    public function isActive(): bool
    {
        return $this->responseData['is_active'] === 1;
    }

    public function getLanguage(): string
    {
        return $this->responseData['language'];
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->responseData;
    }
}
