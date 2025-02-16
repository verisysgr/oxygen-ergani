<?php

namespace OxygenSuite\OxygenErgani\Responses;

use OxygenSuite\OxygenErgani\Traits\HasAttributes;

abstract class Response
{
    use HasAttributes;

    public function __construct(mixed $attributes)
    {
        if (!is_array($attributes)) {
            return;
        }

        $this->attributes = $attributes;
        $this->processData();
    }

    abstract protected function processData(): void;
}
