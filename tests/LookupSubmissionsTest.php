<?php
/** @noinspection ALL */

namespace Tests;

use OxygenSuite\OxygenErgani\Http\Documents\LookupSubmissions;

class LookupSubmissionsTest extends TestCase
{
    public function test_lookup_submissions(): void
    {
        $lookup = new LookupSubmissions('test-access-token');
        $lookup->getConfig()->setHandler($this->mockResponse(200, 'lookup-submissions.php'));
        $response = $lookup->handle();

        $this->assertIsArray($response);
        $this->assertCount(14, $response);

        $row0 = $response[0];
        $this->assertSame(89, $row0['id']);
        $this->assertSame('SixthDay', $row0['code']);
        $this->assertSame('Δηλώση Απασχόλησης την Έκτη Ημέρα', $row0['description']);

        $lastRow = $response[13];
        $this->assertSame(80, $lastRow['id']);
        $this->assertSame('WTOWeek', $lastRow['code']);
        $this->assertSame('Οργάνωση Χρόνου Εργασίας - Σταθερό Εβδομαδιαίο', $lastRow['description']);
    }
}