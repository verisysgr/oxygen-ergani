<?php

namespace OxygenSuite\OxygenErgani\Storage;

use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\RefreshTokenExpiredException;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogin;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationRefresh;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;

abstract class Token implements TokenManager
{
    protected static ?TokenManager $currentTokenManager = null;

    protected string $username;
    protected string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Sets the active token manager.
     *
     * @param  TokenManager|null  $tokenManager
     * @return void
     */
    public static function setCurrentTokenManager(?TokenManager $tokenManager): void
    {
        self::$currentTokenManager = $tokenManager;
    }

    /**
     * Returns the active token manager.
     *
     * @return TokenManager|null
     */
    public static function currentTokenManager(): ?TokenManager
    {
        return self::$currentTokenManager;
    }

    public static function hasTokenManager(): bool
    {
        return self::currentTokenManager() !== null;
    }

    /**
     * @throws ErganiException
     */
    public function authenticate(): ?string
    {
        if (empty($this->username) || empty($this->password) || empty($this->getAccessToken())) {
            $this->clear();
            return null;
        }

        // If the access token is not expired, return it
        if (!$this->isAccessTokenExpired()) {
            return $this->getAccessToken();
        }

        // If the refresh token is empty or expired, re-authenticate
        if (empty($this->getRefreshToken()) || $this->isRefreshTokenExpired()) {
            return $this->loginAndReturnAccessToken();
        }

        // Refresh the access token and the refresh token
        try {
            $this->setAuthToken($this->refresh());
            return $this->getAccessToken();
        } catch (RefreshTokenExpiredException) {
            // If the refresh token has expired, re-authenticate using
            // the username and password.
            return $this->loginAndReturnAccessToken();
        }
    }

    /**
     * @throws ErganiException
     */
    protected function loginAndReturnAccessToken(): ?string
    {
        try {
            $this->setAuthToken($this->login());
            return $this->getAccessToken();
        } catch (AuthenticationException $e) {
            $this->clear();
            return $e;
        }
    }

    /**
     * Authenticates the user using the username and password.
     *
     * @throws ErganiException
     */
    protected function login(): AuthenticationToken
    {
        $login = new AuthenticationLogin();
        return $login->handle($this->username, $this->password);
    }

    /**
     * Refreshes the access token and the refresh token.
     *
     * @return AuthenticationToken
     * @throws ErganiException
     * @throws RefreshTokenExpiredException
     */
    protected function refresh(): AuthenticationToken
    {
        $refresh = new AuthenticationRefresh();
        return $refresh->handle($this->getAccessToken(), $this->getRefreshToken());
    }
}
