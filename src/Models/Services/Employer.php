<?php

namespace OxygenSuite\OxygenErgani\Models\Services;

use OxygenSuite\OxygenErgani\Models\Model;

class Employer extends Model
{
    protected array $expectedOrder = [
        'Id',
        'Afm',
        'Eponimia',
        'Ame',
        'IsInCardSector',
    ];

    public function getId(): ?string
    {
        return $this->get('Id');
    }

    public function setId(?string $id): static
    {
        return $this->set('Id', $id);
    }

    public function getAfm(): ?string
    {
        return $this->get('Afm');
    }

    public function setAfm(?string $afm): static
    {
        return $this->set('Afm', $afm);
    }

    public function getEponimia(): ?string
    {
        return $this->get('Eponimia');
    }

    public function setEponimia(?string $eponimia): static
    {
        return $this->set('Eponimia', $eponimia);
    }

    public function getAme(): ?string
    {
        return $this->get('Ame');
    }

    public function setAme(?string $ame): static
    {
        return $this->set('Ame', $ame);
    }

    public function isInCardSector(): bool
    {
        return $this->get('IsInCardSector') ?? false;
    }

    public function setIsInCardSector(bool $isInCardSector): static
    {
        return $this->set('IsInCardSector', $isInCardSector);
    }
}
