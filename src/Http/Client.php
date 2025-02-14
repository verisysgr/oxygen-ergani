<?php

namespace OxygenSuite\OxygenErgani\Http;

use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;
use OxygenSuite\OxygenErgani\Exceptions\ConnectionException;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\SessionExpiredException;
use OxygenSuite\OxygenErgani\Exceptions\TimeoutException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private ?string $accessToken;
    private Environment $environment;
    private ClientConfig $config;
    private ?ResponseInterface $response;

    /**
     * Create a new client instance. If no environment is provided, the client
     * will default to the test environment.
     *
     * @param  string|null  $accessToken  Access token for the API
     * @param  Environment|null  $environment  Environment for the client
     * @param  ClientConfig|null  $config  Configuration for the client
     */
    public function __construct(?string $accessToken = null, ?Environment $environment = null, ?ClientConfig $config = null)
    {
        $this->accessToken = $accessToken;
        $this->environment = $environment ?? Environment::TEST;
        $this->config = $config ?? new ClientConfig();
    }

    /**
     * Get the environment for the client.
     *
     * @return Environment
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    /**
     * Set the environment for the client.
     *
     * @param  Environment  $environment
     * @return $this
     */
    public function setEnvironment(Environment $environment): static
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * Get the configuration for the client.
     *
     * @return ClientConfig
     */
    public function getConfig(): ClientConfig
    {
        return $this->config;
    }

    /**
     * Set the configuration for the client.
     *
     * @param  ClientConfig  $config
     * @return $this
     */
    public function setConfig(ClientConfig $config): static
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Sets the content type of the request to JSON.
     */
    public function asJson(): static
    {
        $this->config->asJson();
        return $this;
    }

    /**
     * Accepts JSON responses from the server.
     *
     * @return $this
     */
    public function acceptJson(): static
    {
        $this->config->acceptJson();
        return $this;
    }

    /**
     * Returns the status code of the response.
     * @return bool Whether the request was successful
     */
    public function isSuccessful(): bool
    {
        return $this->response?->getStatusCode() === 200;
    }

    /**
     * Make a GET request to the API.
     *
     * @param  string  $uri  The URI to make the request to
     * @param  array|null  $query  The query parameters
     * @param  string|array|null  $body  The body of the request
     *
     * @return $this
     * @throws ErganiException
     */
    protected function get(string $uri, ?array $query = null, string|array|null $body = null): static
    {
        return $this->request('GET', $uri, $body, $query);
    }

    /**
     * Make a request to the API.
     *
     * @throws ErganiException
     */
    protected function request(string $method, string $uri, array|string|null $body = null, ?array $query = null): static
    {
        $this->validateUri($uri);
        $client = $this->createClient();
        $this->response = null;

        try {
            $this->response = $client->request($method, $uri, $this->buildRequestOptions($query, $body));
            $this->validateResponse();

            return $this;
        } catch (GuzzleException $e) {
            $this->handleRequestException($e);
        }
    }

    /**
     * Validate the URI.
     *
     * @param  string  $uri
     * @return void
     */
    protected function validateUri(string $uri): void
    {
        if (empty(trim($uri))) {
            throw new InvalidArgumentException('URI cannot be empty');
        }
    }

    /**
     * Creates a new instance of the Guzzle client with the
     * provided configuration.
     *
     * @return BaseClient
     */
    protected function createClient(): BaseClient
    {
        return new BaseClient([
            'base_uri' => $this->environment->getApiUrl(),
            ...$this->config->getOptions(),
        ]);
    }

    /**
     * Build the request options.
     */
    protected function buildRequestOptions(?array $query = null, array|string|null $body = null): array
    {
        $options = [];

        if (!empty($this->accessToken)) {
            $options[RequestOptions::HEADERS]['Authorization'] = "Bearer $this->accessToken";
        }

        if (!empty($query)) {
            $options[RequestOptions::QUERY] = $query;
        }

        if (is_array($body)) {
            $options[RequestOptions::JSON] = $body;
        } elseif (is_string($body)) {
            $options[RequestOptions::BODY] = $body;
        }

        return $options;
    }

    /**
     * Validate the response.
     *
     * @throws AuthenticationException
     * @throws SessionExpiredException
     */
    protected function validateResponse(): void
    {
        $statusCode = $this->response?->getStatusCode();

        match ($statusCode) {
            401 => throw new AuthenticationException('Invalid authentication credentials'),
            403 => throw new SessionExpiredException('Session has expired'),
            default => null
        };
    }

    /**
     * Returns the status code of the response.
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->response?->getStatusCode() ?? null;
    }

    /**
     * @throws TimeoutException
     * @throws ConnectionException
     * @throws AuthenticationException
     * @throws ErganiException
     */
    protected function handleRequestException(GuzzleException $exception)
    {
        // Specific case for timeout exception (HTTP 28 for cURL)
        // Connection with myDATA was established but the response took too long
        if ($exception instanceof RequestException) {
            $errorNo = $exception->getHandlerContext()['errno'] ?? null;
            if ($errorNo === 28) {
                throw new TimeoutException(previous: $exception);
            }
        }

        $message = $exception->getMessage();

        // In case the endpoint url is wrong or connection timed out, myDATA is unreachable
        if ($exception->getCode() === 0) {
            throw new ConnectionException($message, $exception->getCode(), $exception);
        }

        $contents = json_decode($exception->getResponse()->getBody()->getContents(), true);
        if (is_string($contents) && json_last_error() === JSON_ERROR_NONE) {
            $message = $contents;
        } else {
            if (is_array($contents) && json_last_error() === JSON_ERROR_NONE) {
                $message = $contents['message'] ?? $message;
            }
        }

        // Authentication with myDATA failed
        if ($exception->getCode() === 401) {
            throw new AuthenticationException($message, $exception->getCode(), previous: $exception);
        }

        throw new ErganiException($message, $exception->getCode(), $exception);
    }

    /**
     * Make a POST request to the API.
     *
     * @param  string  $uri  The URI to make the request to
     * @param  array|string|null  $body  The body of the request
     * @param  array|null  $query  The query parameters
     *
     * @return $this
     * @throws ErganiException
     */
    protected function post(string $uri, array|string|null $body = null, ?array $query = null): static
    {
        return $this->request('POST', $uri, $body, $query);
    }

    /**
     * Morphs the JSON response to an array of objects.
     * @param  string  $morphClass  The class to morph the JSON to
     * @return array The array of morphed objects
     */
    protected function morphToArray(string $morphClass): array
    {
        return array_map(fn ($item) => new $morphClass($item), $this->json());
    }

    /**
     * Convert the response to JSON.
     * @return array The JSON response or empty array if invalid
     */
    protected function json(): array
    {
        $json = json_decode($this->contents([]), true);

        return json_last_error() === JSON_ERROR_NONE ? $json : [];
    }

    /**
     * Get the response of the request.
     *
     * @param  mixed|null  $default  The default value to return
     * @return string The response content
     */
    protected function contents(mixed $default = null): string
    {
        return $this->response?->getBody()->getContents() ?? $default;
    }

//    /**
//     * @throws SessionExpiredException
//     */
//    protected function validateResponse(): void
//    {
//        if ($this->response->getHeaderLine('api-token-expired') === 'true') {
//            throw new SessionExpiredException();
//        }
//    }

    /**
     * Morphs the JSON response to an object.
     * @param  string  $morphClass  The class to morph the JSON to
     * @return mixed The morphed object
     */
    protected function morphToClass(string $morphClass): mixed
    {
        return new $morphClass($this->json());
    }
}
