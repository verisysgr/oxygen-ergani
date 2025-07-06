<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Services\EmployerService;
use OxygenSuite\OxygenErgani\Models\Services\Employer;
use Tests\TestCase;

class EmployerServiceTest extends TestCase
{
    public function test_get_employer_success(): void
    {
        $employerService = new EmployerService('test-access-token');
        $employerService->getConfig()->setHandler($this->mockResponse(200, 'employer.json'));

        $employer = $employerService->handle();

        $this->assertInstanceOf(Employer::class, $employer);
        $this->assertSame('12345', $employer->getId());
        $this->assertSame('123456789', $employer->getAfm());
        $this->assertSame('OXYGEN SUITE IKE', $employer->getEponimia());
        $this->assertSame('987654321', $employer->getAme());
        $this->assertTrue($employer->isInCardSector());
    }

    public function test_get_employer_empty_response_throws_exception(): void
    {
        $employerService = new EmployerService('test-access-token');
        $employerService->getConfig()->setHandler($this->mockResponse(200, 'employer-empty.json'));

        $this->expectException(ErganiException::class);
        $this->expectExceptionMessage('Δεν βρέθηκαν στοιχεία εργοδότη.');

        $employerService->handle();
    }

    public function test_get_employer_completely_empty_response_throws_exception(): void
    {
        $employerService = new EmployerService('test-access-token');
        $employerService->getConfig()->setHandler($this->mockResponse(200, 'empty.json'));

        $this->expectException(ErganiException::class);
        $this->expectExceptionMessage('Δεν βρέθηκαν στοιχεία εργοδότη.');

        $employerService->handle();
    }

    public function test_request_body_structure(): void
    {
        $employerService = new EmployerService('test-access-token');

        $reflection = new \ReflectionClass($employerService);
        $method = $reflection->getMethod('requestBody');
        $method->setAccessible(true);

        $body = $method->invoke($employerService);

        $this->assertIsArray($body);
        $this->assertArrayHasKey('ServiceCode', $body);
        $this->assertArrayHasKey('Parameters', $body);
        $this->assertSame('EX_BASE_01', $body['ServiceCode']);
        $this->assertSame([], $body['Parameters']);
    }
}
