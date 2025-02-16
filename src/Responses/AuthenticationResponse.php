<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTime;
use DateTimeZone;

class AuthenticationResponse extends Response
{
    public ?string $accessToken;
    public ?int $accessTokenExpired;
    public ?string $refreshToken;
    public ?DateTime $refreshTokenExpired;

    protected function processData(): void
    {
        $this->accessToken = $this->string('accessToken');
        $this->accessTokenExpired = $this->int('accessTokenExpired');
        $this->refreshToken = $this->string('refreshToken');
        $this->refreshTokenExpired = $this->datetime('refreshTokenExpired')?->setTimezone(new DateTimeZone("UTC"));
    }

    /**
     * Get the access token expiration date in UTC.
     * @return DateTime|null
     */
    public function getAccessTokenExpiresAt(): ?DateTime
    {
        if (empty($this->accessTokenExpired)) {
            return null;
        }

        return $this->datetime('now +'.$this->accessTokenExpired.' seconds');
    }

    /**
     * Check if the access token is expired.
     * @return bool Returns true if the access token is expired, false otherwise.
     */
    public function isAccessTokenExpired(): bool
    {
        return $this->getAccessTokenExpiresAt() < $this->now();
    }

    /**
     * Check if the refresh token is expired.
     * @return bool Returns true if the refresh token is expired, false otherwise.
     */
    public function isRefreshTokenExpired(): bool
    {
        return $this->refreshTokenExpired < $this->now();
    }
}
