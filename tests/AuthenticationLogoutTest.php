<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests;

use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogout;

class AuthenticationLogoutTest extends TestCase
{
    public function test_authentication_logout(): void
    {
        $auth = new AuthenticationLogout('test-access-token');
        $auth->getConfig()->setHandler($this->mockResponse(200, 'authentication-logout.php'));
        $response = $auth->handle('test-refresh-token');

        $this->assertTrue($response);
    }
}
