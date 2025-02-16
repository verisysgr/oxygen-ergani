<?php

namespace OxygenSuite\OxygenErgani\Models;

use OxygenSuite\OxygenErgani\Enums\CardDetailType;

class CardDetail extends Model
{
    public function getTinNumber(): ?string
    {
        return $this->get('f_afm');
    }

    public function setTinNumber(string $tinNumber): static
    {
        return $this->set('f_afm', $tinNumber);
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

    public function getType(): ?CardDetailType
    {
        return $this->get('f_type');
    }

    public function setType(CardDetailType|string $type): static
    {
        return $this->set('f_type', $type);
    }

    public function getReferenceDate(): ?string
    {
        return $this->get('f_reference_date');
    }

    public function setReferenceDate(string $referenceDate): static
    {
        return $this->set('f_reference_date', $referenceDate);
    }

    public function getDate(): ?string
    {
        return $this->get('f_date');
    }

    public function setDate(string $date): static
    {
        return $this->set('f_date', $date);
    }

    public function getReasonCode(): ?string
    {
        return $this->get('f_aitiologia');
    }

    public function setReasonCode(string $reasonCode): static
    {
        return $this->set('f_aitiologia', $reasonCode);
    }
}
