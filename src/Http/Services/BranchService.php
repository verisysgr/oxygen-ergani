<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Responses\BranchResponse;

class BranchService extends ExecuteService
{
    protected function requestBody(): array
    {
        return [
            'ServiceCode' => 'EX_BASE_02',
            'Parameters' => [],
        ];
    }

    /**
     * @throws ErganiException
     */
    public function handle(): array
    {
        $response = $this->fetch()->morphTo(BranchResponse::class);

        if (empty($response->getBranches())) {
            throw new ErganiException('Δεν βρέθηκαν στοιχεία παραρτημάτων.');
        }

        return $response->getBranches();
    }
}
