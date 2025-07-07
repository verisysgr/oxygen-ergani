<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Services\BranchService;
use OxygenSuite\OxygenErgani\Models\Services\Branch;
use Tests\TestCase;

class BranchServiceTest extends TestCase
{
    public function test_get_single_branch_success(): void
    {
        $branchService = new BranchService('test-access-token');
        $branchService->getConfig()->setHandler($this->mockResponse(200, 'branch-single.json'));

        $branches = $branchService->handle();

        $this->assertIsArray($branches);
        $this->assertCount(1, $branches);
        $this->assertInstanceOf(Branch::class, $branches[0]);
        $this->assertSame('001', $branches[0]->getAa());
        $this->assertSame('ΚΕΝΤΡΙΚΟ ΚΑΤΑΣΤΗΜΑ, ΑΘΗΝΑ', $branches[0]->getAddress());
    }

    public function test_get_multiple_branches_success(): void
    {
        $branchService = new BranchService('test-access-token');
        $branchService->getConfig()->setHandler($this->mockResponse(200, 'branch-multiple.json'));

        $branches = $branchService->handle();

        $this->assertIsArray($branches);
        $this->assertCount(3, $branches);

        $this->assertInstanceOf(Branch::class, $branches[0]);
        $this->assertSame('001', $branches[0]->getAa());
        $this->assertSame('ΚΕΝΤΡΙΚΟ ΚΑΤΑΣΤΗΜΑ, ΑΘΗΝΑ', $branches[0]->getAddress());

        $this->assertInstanceOf(Branch::class, $branches[1]);
        $this->assertSame('002', $branches[1]->getAa());
        $this->assertSame('ΠΑΡΑΡΤΗΜΑ ΘΕΣΣΑΛΟΝΙΚΗΣ', $branches[1]->getAddress());

        $this->assertInstanceOf(Branch::class, $branches[2]);
        $this->assertSame('003', $branches[2]->getAa());
        $this->assertSame('ΠΑΡΑΡΤΗΜΑ ΠΑΤΡΩΝ', $branches[2]->getAddress());
    }

    public function test_get_branches_empty_response_throws_exception(): void
    {
        $branchService = new BranchService('test-access-token');
        $branchService->getConfig()->setHandler($this->mockResponse(200, 'branch-empty.json'));

        $this->expectException(ErganiException::class);
        $this->expectExceptionMessage('Δεν βρέθηκαν στοιχεία παραρτημάτων.');

        $branchService->handle();
    }

    public function test_get_branches_missing_pararthma_throws_exception(): void
    {
        $branchService = new BranchService('test-access-token');
        $branchService->getConfig()->setHandler($this->mockResponse(200, 'branch-missing-pararthma.json'));

        $this->expectException(ErganiException::class);
        $this->expectExceptionMessage('Δεν βρέθηκαν στοιχεία παραρτημάτων.');

        $branchService->handle();
    }

    public function test_get_branches_missing_service_throws_exception(): void
    {
        $branchService = new BranchService('test-access-token');
        $branchService->getConfig()->setHandler($this->mockResponse(200, 'branch-missing-service.json'));

        $this->expectException(ErganiException::class);
        $this->expectExceptionMessage('Δεν βρέθηκαν στοιχεία παραρτημάτων.');

        $branchService->handle();
    }

    public function test_get_branches_completely_empty_response_throws_exception(): void
    {
        $branchService = new BranchService('test-access-token');
        $branchService->getConfig()->setHandler($this->mockResponse(200, 'empty.json'));

        $this->expectException(ErganiException::class);
        $this->expectExceptionMessage('Δεν βρέθηκαν στοιχεία παραρτημάτων.');

        $branchService->handle();
    }

    public function test_request_body_structure(): void
    {
        $branchService = new BranchService('test-access-token');

        $reflection = new \ReflectionClass($branchService);
        $method = $reflection->getMethod('requestBody');
        $method->setAccessible(true);

        $body = $method->invoke($branchService);

        $this->assertIsArray($body);
        $this->assertArrayHasKey('ServiceCode', $body);
        $this->assertArrayHasKey('Parameters', $body);
        $this->assertSame('EX_BASE_02', $body['ServiceCode']);
        $this->assertSame([], $body['Parameters']);
    }
}
