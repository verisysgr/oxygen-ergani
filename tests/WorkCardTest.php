<?php
/** @noinspection ALL */

namespace Tests;

use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogout;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard;
use OxygenSuite\OxygenErgani\Models\Card;

class WorkCardTest extends TestCase
{
    public function test_schema(): void
    {
        $workCard = new WorkCard("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'work-card-schema.php'));
        $response = $workCard->schema();

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_work_card(): void
    {
        $workCard = new WorkCard("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.php'));
        $response = $workCard->handle(new Card());

        $this->assertIsArray($response);
    }
}