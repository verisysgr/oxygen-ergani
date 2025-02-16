<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTime;

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
        $this->refreshTokenExpired = $this->datetime('refreshTokenExpired');
    }
}
