<?php

namespace OxygenSuite\OxygenErgani\Models\WTO;

use OxygenSuite\OxygenErgani\Models\Model;

class WTOAnalytics extends Model
{
    protected array $expectedOrder = [
        "f_type",
        "f_from",
        "f_to",
        "f_year",
        "f_req_days",
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

    public function getYear(): ?string
    {
        return $this->get('f_year');
    }

    public function setYear(string $year): static
    {
        return $this->set('f_year', $year);
    }

    public function getEntitledDays(): ?string
    {
        return $this->get('f_req_days');
    }

    public function setEntitledDays(string $days): static
    {
        return $this->set('f_req_days', str_pad($days, 3, '0', STR_PAD_LEFT));
    }
}
