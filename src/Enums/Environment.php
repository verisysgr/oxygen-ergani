<?php

namespace OxygenSuite\OxygenErgani\Enums;

enum Environment
{
    case PRODUCTION;
    case TEST;

    public function getApiUrl(): string
    {
        return match ($this) {
            self::PRODUCTION => 'https://eservices.yeka.gr/WebservicesAPI/api/',
            self::TEST => 'https://trialeservices.yeka.gr/WebServicesAPI/api/',
        };
    }
}
