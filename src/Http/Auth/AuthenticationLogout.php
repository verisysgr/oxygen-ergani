<?php

namespace OxygenSuite\OxygenErgani\Http\Auth;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\SessionExpiredException;
use OxygenSuite\OxygenErgani\Http\Client;

class AuthenticationLogout extends Client
{
    private const URI = 'Authentication/Logout';

    /**
     * Deletes the refresh token from the api server.
     *
     * @param  string  $refreshToken
     * @return bool
     * @throws ErganiException
     * @throws SessionExpiredException
     */
    public function handle(string $refreshToken): bool
    {
        return $this->asJson()
            ->post(self::URI, json_encode($refreshToken))
            ->isSuccessful();
    }
}
