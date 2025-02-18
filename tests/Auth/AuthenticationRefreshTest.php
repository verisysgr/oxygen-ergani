<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Auth;

use OxygenSuite\OxygenErgani\Exceptions\RefreshTokenExpiredException;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationRefresh;
use Tests\TestCase;

class AuthenticationRefreshTest extends TestCase
{
    public function test_authentication_refresh(): void
    {
        $auth = new AuthenticationRefresh();
        $auth->getConfig()->setHandler($this->mockResponse(200, 'authentication.json'));
        $response = $auth->handle('old-test-access-token', 'old-test-refresh-token');

        $this->assertNotNull($response);
        $this->assertSame('test-access-token', $response->accessToken);
        $this->assertSame('test-refresh-token', $response->refreshToken);
        $this->assertDates('2025-02-21T14:44:28.2731304+02:00', $response->refreshTokenExpiresAt);
    }

    public function test_authentication_failed_refresh(): void
    {
        $this->expectException(RefreshTokenExpiredException::class);

        $auth = new AuthenticationRefresh();
        $auth->getConfig()->setHandler($this->mockResponse(401));
        $response = $auth->handle('old-test-access-token', 'old-test-refresh-token');

        $this->assertNotNull($response);
        $this->assertSame('test-access-token', $response->accessToken);
        $this->assertSame('test-refresh-token', $response->refreshToken);
        $this->assertDates('2025-02-21T14:44:28.2731304+02:00', $response->refreshTokenExpiresAt);
    }
}
