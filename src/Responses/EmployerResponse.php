<?php

namespace OxygenSuite\OxygenErgani\Responses;

use OxygenSuite\OxygenErgani\Models\Services\Employer;

class EmployerResponse extends Response
{
    public ?string $id;
    public ?string $afm;
    public ?string $eponimia;
    public ?string $ame;
    public bool $isInCardSector;

    protected function processData(): void
    {
        if (!isset($this->attributes['EX_BASE_01'])) {
            $this->attributes = [];
            return;
        }

        if (!isset($this->attributes['EX_BASE_01']['Ergodotis'])) {
            $this->attributes = [];
            return;
        }

        $this->attributes = $this->attributes['EX_BASE_01']['Ergodotis'];

        $this->id = $this->string('Id');
        $this->afm = $this->string('Afm');
        $this->eponimia = $this->string('Eponimia');
        $this->ame = $this->string('Ame');
        $this->isInCardSector = $this->bool('IsInCardSector', false);
    }

    public function getEmployer(): ?Employer
    {
        if (empty($this->attributes)) {
            return null;
        }

        $employer = new Employer();
        $employer->setId($this->id)
                 ->setAfm($this->afm)
                 ->setEponimia($this->eponimia)
                 ->setAme($this->ame)
                 ->setIsInCardSector($this->isInCardSector);

        return $employer;
    }
}
