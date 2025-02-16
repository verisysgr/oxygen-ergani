<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Auth;

use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogout;
use Tests\TestCase;

class AuthenticationLogoutTest extends TestCase
{
    public function test_authentication_logout(): void
    {
        $auth = new AuthenticationLogout('test-access-token');
        $auth->getConfig()->setHandler($this->mockResponse(200, 'empty.json'));
        $response = $auth->handle('test-refresh-token');

        $this->assertTrue($response);
    }
}
