<?php
/** @noinspection ALL */

namespace Tests;

use OxygenSuite\OxygenErgani\Http\Auth\Authentication;

class AuthenticationTest extends TestCase
{
    public function test_authentication(): void
    {
        $auth = new Authentication();
        $auth->getConfig()->setHandler($this->mockResponse(200, 'authentication.php'));
        $response = $auth->handle('username', 'password');

        $this->assertNotNull($response);
        $this->assertSame('test-access-token', $response->accessToken);
        $this->assertSame(10800, $response->accessTokenExpired);
        $this->assertSame('test-refresh-token', $response->refreshToken);
        $this->assertDates('2025-02-21T14:44:28.2731304+02:00', $response->refreshTokenExpired);
    }
}