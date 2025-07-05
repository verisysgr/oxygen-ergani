<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Client;

abstract class ExecuteService extends Client
{
    private const URI = 'WebServices/ExecuteService';

    /**
     * Execute the service with the provided body.
     *
     * @return static
     * @throws ErganiException
     */
    protected function fetch(): static
    {
        return $this->post(self::URI, $this->requestBody());
    }

    abstract protected function requestBody(): string;
}
