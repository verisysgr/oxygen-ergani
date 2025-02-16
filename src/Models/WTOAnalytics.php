<?php

namespace OxygenSuite\OxygenErgani\Models;

class WTOAnalytics extends Model
{
    protected array $expectedOrder = [
        "f_type",
        "f_from",
        "f_to",
    ];

    public function getType(): ?string
    {
        return $this->get('f_type');
    }

    public function setType(string $type): static
    {
        return $this->set('f_type', $type);
    }

    public function getFromTime(): ?string
    {
        return $this->get('f_from');
    }

    public function setFromTime(string $fromDate): static
    {
        return $this->set('f_from', $fromDate);
    }

    public function getToTime(): ?string
    {
        return $this->get('f_to');
    }

    public function setToTime(string $toDate): static
    {
        return $this->set('f_to', $toDate);
    }
}
