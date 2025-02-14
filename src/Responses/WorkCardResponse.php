<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTime;

class WorkCardResponse extends Response
{
    public ?string $id;
    public ?string $protocol;
    public ?DateTime $submissionDate;

    protected function processData(): void
    {
        $this->id = $this->string('id');
        $this->protocol = $this->string('protocol');
        $this->submissionDate = $this->datetime('submissionDate');
    }
}