<?php

namespace OxygenSuite\OxygenErgani\Enums;

enum Environment
{
    case PRODUCTION;
    case TEST;

    public function getApiUrl(): string
    {
        return match ($this) {
            self::PRODUCTION => 'https://' . $this->getHost('ERGANI_PRODUCTION_HOST', 'eservices.yeka.gr') . '/WebservicesAPI/api/',
            self::TEST => 'https://' . $this->getHost('ERGANI_TEST_HOST', 'trialeservices.yeka.gr') . '/WebServicesAPI/api/',
        };
    }

    private function getHost(string $key, string $default): string
    {
        $value = getenv($key);

        if ($value === false || $value === '') {
            return $default;
        }

        return $value;
    }
}
