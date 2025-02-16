<?php

namespace OxygenSuite\OxygenErgani\Http\Auth;

use OxygenSuite\OxygenErgani\Enums\UserType;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Client;
use OxygenSuite\OxygenErgani\Responses\AuthenticationResponse;

class AuthenticationLogin extends Client
{
    private const URI = 'Authentication';

    /**
     * Authenticates the user.
     *
     * Access token is used to authenticate the user for subsequent
     * requests, while the refresh token is used to obtain a new access token
     *
     * @param  string  $username
     * @param  string  $password
     * @param  UserType|string  $userType
     * @return AuthenticationResponse
     * @throws ErganiException
     */
    public function handle(string $username, string $password, UserType|string $userType = UserType::EXTERNAL): AuthenticationResponse
    {
        return $this->post(self::URI, [
            'Username' => $username,
            'Password' => $password,
            'Usertype' => $userType instanceof UserType ? $userType->value : $userType,
        ])->morphTo(AuthenticationResponse::class);
    }
}
