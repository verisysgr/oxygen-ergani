<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Services\GetEmployer;
use OxygenSuite\OxygenErgani\Models\Services\Employer;
use Tests\TestCase;

class GetEmployerTest extends TestCase
{
    public function test_get_employer_success(): void
    {
        $getEmployer = new GetEmployer('test-access-token');
        $getEmployer->getConfig()->setHandler($this->mockResponse(200, 'employer.json'));

        $employer = $getEmployer->handle();

        $this->assertInstanceOf(Employer::class, $employer);
        $this->assertSame('12345', $employer->getId());
        $this->assertSame('123456789', $employer->getAfm());
        $this->assertSame('OXYGEN SUITE IKE', $employer->getEponimia());
        $this->assertSame('987654321', $employer->getAme());
        $this->assertTrue($employer->isInCardSector());
    }

    public function test_get_employer_empty_response_throws_exception(): void
    {
        $getEmployer = new GetEmployer('test-access-token');
        $getEmployer->getConfig()->setHandler($this->mockResponse(200, 'employer-empty.json'));

        $this->expectException(ErganiException::class);
        $this->expectExceptionMessage('Δεν βρέθηκαν στοιχεία εργοδότη.');

        $getEmployer->handle();
    }

    public function test_get_employer_completely_empty_response_throws_exception(): void
    {
        $getEmployer = new GetEmployer('test-access-token');
        $getEmployer->getConfig()->setHandler($this->mockResponse(200, 'empty.json'));

        $this->expectException(ErganiException::class);
        $this->expectExceptionMessage('Δεν βρέθηκαν στοιχεία εργοδότη.');

        $getEmployer->handle();
    }

    public function test_request_body_structure(): void
    {
        $getEmployer = new GetEmployer('test-access-token');

        $reflection = new \ReflectionClass($getEmployer);
        $method = $reflection->getMethod('requestBody');
        $method->setAccessible(true);

        $body = $method->invoke($getEmployer);
        $decodedBody = json_decode($body, true);

        $this->assertIsArray($decodedBody);
        $this->assertArrayHasKey('ServiceCode', $decodedBody);
        $this->assertArrayHasKey('Parameters', $decodedBody);
        $this->assertSame('EX_BASE_01', $decodedBody['ServiceCode']);
        $this->assertSame([], $decodedBody['Parameters']);
    }
}
