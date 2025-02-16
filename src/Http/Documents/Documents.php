<?php

namespace OxygenSuite\OxygenErgani\Http\Documents;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Client;

abstract class Documents extends Client
{
    private const URI = 'Documents';

    /**
     * @throws ErganiException
     */
    public function schema(): array
    {
        return $this->get(self::URI.'/'.$this->action())->json();
    }

    /**
     * @throws ErganiException
     */
    protected function submit(array $body): static
    {
        return $this->post(self::URI.'/'.$this->action(), $body);
    }

    abstract protected function action(): string;
}
