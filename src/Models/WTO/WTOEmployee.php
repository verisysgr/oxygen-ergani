<?php

namespace OxygenSuite\OxygenErgani\Models\WTO;

use OxygenSuite\OxygenErgani\Models\Model;

class WTOEmployee extends Model
{
    protected array $expectedOrder = [
        'f_afm',
        'f_eponymo',
        'f_onoma',
        'f_day',
        'f_date',
        'ErgazomenosAnalytics',
    ];

    public function getTin(): ?string
    {
        return $this->get('f_afm');
    }

    public function setTin(string $tin): static
    {
        return $this->set('f_afm', $tin);
    }

    public function getLastName(): ?string
    {
        return $this->get('f_eponymo');
    }

    public function setLastName(string $lastName): static
    {
        return $this->set('f_eponymo', $lastName);
    }

    public function getFirstName(): ?string
    {
        return $this->get('f_onoma');
    }

    public function setFirstName(string $firstName): static
    {
        return $this->set('f_onoma', $firstName);
    }

    public function getDay(): ?int
    {
        return $this->get('f_day');
    }

    public function setDay(int $day): static
    {
        return $this->set('f_day', $day);
    }

    public function getDate(): ?string
    {
        return $this->get('f_date');
    }

    public function setDate(string $date): static
    {
        return $this->set('f_date', $date);
    }

    public function getAnalytics(): ?array
    {
        return $this->get('ErgazomenosAnalytics')['ErgazomenosWTOAnalytics'] ?? null;
    }

    public function getAnalytic(int $index): ?WTOAnalytics
    {
        return $this->get('ErgazomenosAnalytics')['ErgazomenosWTOAnalytics'][$index] ?? null;
    }

    public function setAnalytics(array $analytics): static
    {
        return $this->set('ErgazomenosAnalytics', ['ErgazomenosWTOAnalytics' => $analytics]);
    }

    public function addAnalytics(WTOAnalytics $analytics): static
    {
        $currentAnalytics = $this->getAnalytics() ?? [];
        $currentAnalytics[] = $analytics;
        return $this->setAnalytics($currentAnalytics);
    }
}
