<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents;

use OxygenSuite\OxygenErgani\Enums\CardDetailType;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use Tests\TestCase;

class WorkCardTest extends TestCase
{
    public function test_work_card_submit(): void
    {
        $card = Card::make()
            ->setEmployerTin('999999999')
            ->setBranchCode(0)
            ->setComments('test-comments')
            ->addDetails(
                CardDetail::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setType(CardDetailType::CHECK_IN)
                    ->setReferenceDate(date('Y-m-d'))
                    ->setDate(date('Y-m-d\TH:i:s.uP'))
                    ->setReasonCode(null)
            );

        $workCard = new WorkCard('test-access-token');
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $response = $workCard->handle($card);

        $this->assertIsArray($response);
        $this->assertSame('92', $response[0]->id);
        $this->assertSame('ΕΥΣ92', $response[0]->protocol);
        $this->assertSame('04/05/2022 01:13', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_work_card_schema(): void
    {
        $workCard = new WorkCard("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'work-card-schema.json'));
        $schema = $workCard->schema();

        $this->assertIsArray($schema);
        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_work_card_pdf(): void
    {
        $workCard = new WorkCard("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'pdf.txt'));
        $response = $workCard->pdf("ΕΥΣ92", 20220504);

        $this->assertNotNull($response);
        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_card_model(): void
    {
        $card = Card::make()
            ->setEmployerTin('999999999')
            ->setBranchCode(0)
            ->setComments('test-comments')
            ->addDetails([
                CardDetail::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setReferenceDate('2025-02-21')
                    ->setDate('2025-02-21 15:48:00')
                    ->setType(CardDetailType::CHECK_IN),

                CardDetail::make()
                    ->setTin('777777777')
                    ->setFirstName('Jane')
                    ->setLastName('Doe')
                    ->setReferenceDate('2025-02-21')
                    ->setDate('2025-02-21 15:48:00')
                    ->setType(CardDetailType::CHECK_IN)
            ]);

        $this->assertSame('999999999', $card->getEmployerTin());
        $this->assertSame(0, $card->getBranchCode());
        $this->assertSame('test-comments', $card->getComments());
        $this->assertNotNull($card->getDetails());
        $this->assertCount(2, $card->getDetails());

        $this->assertSame('888888888', $card->getDetails(0)->getTinNumber());
        $this->assertSame('John', $card->getDetails(0)->getFirstName());
        $this->assertSame('Doe', $card->getDetails(0)->getLastName());
        $this->assertSame('2025-02-21', $card->getDetails(0)->getReferenceDate());
        $this->assertSame('2025-02-21 15:48:00', $card->getDetails(0)->getDate());
        $this->assertSame(CardDetailType::CHECK_IN, $card->getDetails(0)->getType());

        $this->assertSame('777777777', $card->getDetails(1)->getTinNumber());
        $this->assertSame('Jane', $card->getDetails(1)->getFirstName());
        $this->assertSame('Doe', $card->getDetails(1)->getLastName());
        $this->assertSame('2025-02-21', $card->getDetails(1)->getReferenceDate());
        $this->assertSame('2025-02-21 15:48:00', $card->getDetails(1)->getDate());
        $this->assertSame(CardDetailType::CHECK_IN, $card->getDetails(1)->getType());
    }
}
