<?php

namespace OxygenSuite\OxygenErgani\Http\Auth;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\SessionExpiredException;
use OxygenSuite\OxygenErgani\Http\Client;
use OxygenSuite\OxygenErgani\Responses\AuthenticationResponse;

class AuthenticationRefresh extends Client
{
    private const URI = 'Authentication/Refresh';

    /**
     * Refreshes the access and the refresh token.
     *
     * @param  string  $accessToken
     * @param  string  $refreshToken
     * @return AuthenticationResponse
     * @throws ErganiException
     * @throws SessionExpiredException
     */
    public function handle(string $accessToken, string $refreshToken): AuthenticationResponse
    {
        return $this->post(self::URI, [
            'AccessToken' => $accessToken,
            'RefreshToken' => $refreshToken,
        ])->morphToClass(AuthenticationResponse::class);
    }
}