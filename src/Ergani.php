<?php

namespace OxygenSuite\OxygenErgani;

use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Enums\UserType;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogin;
use OxygenSuite\OxygenErgani\Http\ClientConfig;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard;
use OxygenSuite\OxygenErgani\Http\Services\ServicesList;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;
use OxygenSuite\OxygenErgani\Responses\WorkCardResponse;

class Ergani
{
    private ?string $accessToken;
    private Environment $environment;
    private ?ClientConfig $config;

    public function __construct(?string $accessToken = null, ?Environment $environment = Environment::TEST, ?ClientConfig $config = null)
    {
        $this->accessToken = $accessToken;
        $this->environment = $environment;
        $this->config = $config ?? new ClientConfig();
    }

    /**
     * @throws ErganiException
     */
    public function authenticate(string $username, string $password, UserType $userType = UserType::EXTERNAL): AuthenticationToken
    {
        $auth = new AuthenticationLogin(null, $this->environment, $this->config);
        return $auth->handle($username, $password, $userType);
    }

    /**
     * @throws ErganiException
     */
    public function getServices(): array
    {
        $services = new ServicesList($this->accessToken, $this->environment, $this->config);
        return $services->handle();
    }

    /**
     * @throws ErganiException
     */
    public function workCardSchema(): array
    {
        $workCard = new WorkCard($this->accessToken, $this->environment, $this->config);
        return $workCard->schema();
    }

    /**
     * @return WorkCardResponse[]
     * @throws ErganiException
     */
    public function sendWorkCards(Card|array $cards): array
    {
        $workCard = new WorkCard($this->accessToken, $this->environment, $this->config);
        return $workCard->handle($cards);
    }
}
