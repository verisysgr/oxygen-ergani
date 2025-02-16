<?php

namespace OxygenSuite\OxygenErgani\Http\Documents;

use DateTime;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Client;

abstract class Documents extends Client
{
    private const URI = 'Documents';

    /**
     * @throws ErganiException
     */
    protected function submit(array $body): static
    {
        return $this->post($this->uri(), $body);
    }

    /**
     * @throws ErganiException
     */
    public function schema(): array
    {
        return $this->get($this->uri())->json();
    }

    /**
     * @throws ErganiException
     */
    public function pdf(string $protocol, DateTime|int|string $submittedDate): string
    {
        return $this->get($this->uri(), [
            'protocol' => $protocol,
            'submittedDate' => $submittedDate instanceof DateTime ? $submittedDate->format('Ymd') : $submittedDate,
        ])->contents("");
    }

    protected function uri(): string
    {
        return self::URI.'/'.$this->action();
    }

    abstract protected function action(): string;
}
