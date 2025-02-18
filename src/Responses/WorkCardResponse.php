<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTimeInterface;

class WorkCardResponse extends Response
{
    public ?string $id;
    public ?string $protocol;
    public ?DateTimeInterface $submissionDate;

    protected function processData(): void
    {
        $this->id = $this->string('id');
        $this->protocol = $this->string('protocol');
        $this->submissionDate = $this->date('submitDate', 'd/m/Y H:i');
    }
}
