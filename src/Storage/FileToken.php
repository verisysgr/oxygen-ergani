<?php

namespace OxygenSuite\OxygenErgani\Storage;

use DateTime;
use DateTimeImmutable;
use Error;
use OxygenSuite\OxygenErgani\Http\Client;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;

class FileToken extends Token
{
    private string $filename;
    private ?AuthenticationToken $token = null;

    public function __construct(string $username, string $password)
    {
        parent::__construct($username, $password);

        if (!empty(trim($username)) && !empty(trim($password))) {
            $this->filename = $this->generateFilename();
            $this->readFromFile();
        }
    }

    public function getAccessToken(): ?string
    {
        return $this->token?->accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->token?->refreshToken;
    }

    public function isAccessTokenExpired(): bool
    {
        return $this->token?->accessTokenExpiresAt < new DateTime();
    }

    public function isRefreshTokenExpired(): bool
    {
        return $this->token?->refreshTokenExpiresAt < new DateTime();
    }

    public function setAuthToken(AuthenticationToken $token): static
    {
        $this->token = $token;

        $this->saveToFile();
        return $this;
    }

    public function failedAuthentication(): static
    {
        $this->token = null;
        $this->deleteFile();
        return $this;
    }

    public function authToken(): ?AuthenticationToken
    {
        return $this->token;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function generateFilename(): string
    {
        $env = Client::getDefaultEnvironment()?->name ?? '';
        return md5($this->username.'-'.$this->password.'-'.$env);
    }

    public function saveToFile(): void
    {
        if (empty($this->filename)) {
            return;
        }

        if (!is_dir(self::dir())) {
            mkdir(self::dir());
        }

        $token = $this->token;
        file_put_contents($this->path(), json_encode([
            'token' => [
                'accessToken' => $token->accessToken ?? null,
                'accessTokenExpiresAt' => $token->accessTokenExpiresAt?->getTimestamp() ?? null,
                'refreshToken' => $token->refreshToken ?? null,
                'refreshTokenExpiresAt' => $token->refreshTokenExpiresAt?->getTimestamp() ?? null,
            ],
        ]));
    }

    public function readFromFile(): void
    {
        if (!$this->fileExists()) {
            return;
        }

        $data = json_decode(file_get_contents($this->path()), true);
        if (empty($data)) {
            $this->deleteFile();
            throw new Error('Corrupted token file.');
        }

        $token = $data['token'] ?? [];
        $this->token = new AuthenticationToken();
        $this->token->accessToken = $token['accessToken'] ?? null;
        $this->token->accessTokenExpiresAt = (new DateTimeImmutable())->setTimestamp($token['accessTokenExpiresAt'] ?? null);
        $this->token->refreshToken = $token['refreshToken'] ?? null;
        $this->token->refreshTokenExpiresAt = (new DateTimeImmutable())->setTimestamp($token['refreshTokenExpiresAt'] ?? null);
    }

    public function deleteFile(): void
    {
        $path = $this->path();
        if (file_exists($path) && is_file($path)) {
            unlink($path);
        }
    }

    public function fileExists(): bool
    {
        $path = $this->path();

        return file_exists($path) && is_file($path);
    }

    public static function dir(): string
    {
        return dirname(__DIR__, 2).'/cache';
    }

    public function path(): string
    {
        return self::dir().'/'.$this->filename.'.json';
    }

    /**
     * Clears all cache tokens.
     *
     * @return void
     */
    public static function forgetAllTokens(): void
    {
        $files = glob(self::dir().'/*.json');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public static function fake(string $username, string $password): FakeFileToken
    {
        return new FakeFileToken($username, $password);
    }
}
