<?php

namespace OxygenSuite\OxygenErgani\Models\Services;

use OxygenSuite\OxygenErgani\Models\Model;

class Branch extends Model
{
    protected array $expectedOrder = [
        'Aa',
        'Address',
    ];

    public function getAa(): ?string
    {
        return $this->get('Aa');
    }

    public function setAa(?string $aa): static
    {
        return $this->set('Aa', $aa);
    }

    public function getAddress(): ?string
    {
        return $this->get('Address');
    }

    public function setAddress(?string $address): static
    {
        return $this->set('Address', $address);
    }
}
