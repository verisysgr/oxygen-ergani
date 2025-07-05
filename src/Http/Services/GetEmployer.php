<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Models\Services\Employer;
use OxygenSuite\OxygenErgani\Responses\EmployerResponse;

class GetEmployer extends ExecuteService
{
    protected function requestBody(): string
    {
        $body = [
            'ServiceCode' => 'EX_BASE_01',
            'Parameters' => [],
        ];

        return json_encode($body);
    }

    /**
     * @throws ErganiException
     */
    public function handle(): Employer
    {
        $employerResponse = $this->fetch()->morphTo(EmployerResponse::class);

        if (empty($employerResponse->getEmployer())) {
            throw new ErganiException('Δεν βρέθηκαν στοιχεία εργοδότη.');
        }

        return $employerResponse->getEmployer();
    }
}
