<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents;

use OxygenSuite\OxygenErgani\Http\Documents\DailyWorkTime;
use OxygenSuite\OxygenErgani\Http\Documents\HolidayWorkTime;
use OxygenSuite\OxygenErgani\Http\Documents\WeeklyWorkTime;
use OxygenSuite\OxygenErgani\Models\WTO\WTO;
use OxygenSuite\OxygenErgani\Models\WTO\WTOAnalytics;
use OxygenSuite\OxygenErgani\Models\WTO\WTOEmployee;
use Tests\TestCase;

class WTOTest extends TestCase
{
    public function test_work_card_submit(): void
    {
        $card = WTO::make()
            ->setBranchCode('01')
            ->setRelatedProtocol("ΕΣΠ27")
            ->setRelatedDate("21/02/2025")
            ->setComments('test-comments')
            ->setFromDate("21/02/2025")
            ->setToDate("21/02/2025")
            ->addEmployee(
                WTOEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDate('21/02/2025')
                    ->addAnalytics(
                        WTOAnalytics::make()
                            ->setType('type')
                            ->setFromTime('09:00')
                            ->setToTime('17:00')
                    )
            );

        $wto = new DailyWorkTime('test-access-token');
        $wto->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wto->handle($card);

        $this->assertTrue($wto->isSuccessful());
    }

    public function test_work_card_schema(): void
    {
        $workCard = new DailyWorkTime("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'wto-schema.json'));
        $workCard->schema();

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_work_card_pdf(): void
    {
        $workCard = new DailyWorkTime("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'pdf.txt'));
        $workCard->pdf("ΕΥΣ92", 19800410);

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_card_model(): void
    {
        $card = WTO::make()
            ->setBranchCode('01')
            ->setRelatedProtocol("ΕΣΠ27")
            ->setRelatedDate("21/02/2025")
            ->setComments('test-comments')
            ->setFromDate("21/02/2025")
            ->setToDate("21/02/2025")
            ->addEmployee(
                WTOEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDate('21/02/2025')
                    ->addAnalytics(
                        WTOAnalytics::make()
                            ->setType('type')
                            ->setFromTime('09:00')
                            ->setToTime('14:00')
                    )
                    ->addAnalytics(
                        WTOAnalytics::make()
                            ->setType('type')
                            ->setFromTime('18:00')
                            ->setToTime('21:00')
                    )
            )
            ->addEmployee(
                WTOEmployee::make()
                    ->setTin('777777777')
                    ->setFirstName('Jane')
                    ->setLastName('Doe')
                    ->setDate('21/02/2025')
                    ->addAnalytics(
                        WTOAnalytics::make()
                            ->setType('type')
                            ->setFromTime('09:00')
                            ->setToTime('17:00')
                    )
            );

        $this->assertSame('01', $card->getBranchCode());
        $this->assertSame('ΕΣΠ27', $card->getRelatedProtocol());
        $this->assertSame('21/02/2025', $card->getRelatedDate());
        $this->assertSame('test-comments', $card->getComments());
        $this->assertSame('21/02/2025', $card->getFromDate());
        $this->assertSame('21/02/2025', $card->getToDate());
        $this->assertIsArray($card->getEmployees());
        $this->assertCount(2, $card->getEmployees());

        $this->assertSame('888888888', $card->getEmployee(0)->getTin());
        $this->assertSame('John', $card->getEmployee(0)->getFirstName());
        $this->assertSame('Doe', $card->getEmployee(0)->getLastName());
        $this->assertSame('21/02/2025', $card->getEmployee(0)->getDate());
        $this->assertIsArray($card->getEmployee(0)->getAnalytics());
        $this->assertCount(2, $card->getEmployee(0)->getAnalytics());
        $this->assertSame('type', $card->getEmployee(0)->getAnalytic(0)->getType());
        $this->assertSame('09:00', $card->getEmployee(0)->getAnalytic(0)->getFromTime());
        $this->assertSame('14:00', $card->getEmployee(0)->getAnalytic(0)->getToTime());
        $this->assertSame('type', $card->getEmployee(0)->getAnalytic(1)->getType());
        $this->assertSame('18:00', $card->getEmployee(0)->getAnalytic(1)->getFromTime());
        $this->assertSame('21:00', $card->getEmployee(0)->getAnalytic(1)->getToTime());

        $this->assertSame('777777777', $card->getEmployee(1)->getTin());
        $this->assertSame('Jane', $card->getEmployee(1)->getFirstName());
        $this->assertSame('Doe', $card->getEmployee(1)->getLastName());
        $this->assertSame('21/02/2025', $card->getEmployee(1)->getDate());
        $this->assertIsArray($card->getEmployee(1)->getAnalytics());
        $this->assertCount(1, $card->getEmployee(1)->getAnalytics());
        $this->assertSame('type', $card->getEmployee(1)->getAnalytic(0)->getType());
        $this->assertSame('09:00', $card->getEmployee(1)->getAnalytic(0)->getFromTime());
        $this->assertSame('17:00', $card->getEmployee(1)->getAnalytic(0)->getToTime());
    }

    public function test_weekly_work_time_submit(): void
    {
        $card = WTO::make()
            ->setBranchCode('01')
            ->setRelatedProtocol("ΕΣΠ27")
            ->setRelatedDate("21/02/2025")
            ->setComments('weekly-comments')
            ->setFromDate("24/02/2025")
            ->setToDate("28/02/2025")
            ->addEmployee(
                WTOEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDay(1)
                    ->addAnalytics(
                        WTOAnalytics::make()
                            ->setType('type')
                            ->setFromTime('09:00')
                            ->setToTime('17:00')
                    )
            );

        $wto = new WeeklyWorkTime('test-access-token');
        $wto->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wto->handle($card);

        $this->assertTrue($wto->isSuccessful());
    }

    public function test_weekly_work_time_schema(): void
    {
        $workCard = new WeeklyWorkTime("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'wto-schema.json'));
        $workCard->schema();

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_weekly_work_time_pdf(): void
    {
        $workCard = new WeeklyWorkTime("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'pdf.txt'));
        $workCard->pdf("ΕΥΣ92", 19800410);

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_employee_day_field(): void
    {
        $employee = WTOEmployee::make()
            ->setTin('888888888')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setDay(3)
            ->addAnalytics(
                WTOAnalytics::make()
                    ->setType('type')
                    ->setFromTime('09:00')
                    ->setToTime('17:00')
            );

        $this->assertSame(3, $employee->getDay());
        $this->assertSame('888888888', $employee->getTin());
        $this->assertSame('John', $employee->getFirstName());
        $this->assertSame('Doe', $employee->getLastName());
    }

    public function test_employee_day_field_in_sorted_array(): void
    {
        $employee = WTOEmployee::make()
            ->setTin('888888888')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setDay(5)
            ->addAnalytics(
                WTOAnalytics::make()
                    ->setType('type')
                    ->setFromTime('09:00')
                    ->setToTime('17:00')
            );

        $array = $employee->toSortedArray();

        $this->assertArrayHasKey('f_day', $array);
        $this->assertSame(5, $array['f_day']);

        $keys = array_keys($array);
        $this->assertSame(['f_afm', 'f_eponymo', 'f_onoma', 'f_day', 'ErgazomenosAnalytics'], $keys);
    }

    public function test_weekly_model_with_multiple_days(): void
    {
        $card = WTO::make()
            ->setBranchCode('01')
            ->setFromDate("24/02/2025")
            ->setToDate("28/02/2025")
            ->addEmployee(
                WTOEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDay(1)
                    ->addAnalytics(
                        WTOAnalytics::make()
                            ->setType('type')
                            ->setFromTime('09:00')
                            ->setToTime('17:00')
                    )
            )
            ->addEmployee(
                WTOEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDay(2)
                    ->addAnalytics(
                        WTOAnalytics::make()
                            ->setType('type')
                            ->setFromTime('09:00')
                            ->setToTime('17:00')
                    )
            )
            ->addEmployee(
                WTOEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDay(3)
                    ->addAnalytics(
                        WTOAnalytics::make()
                            ->setType('type')
                            ->setFromTime('09:00')
                            ->setToTime('17:00')
                    )
            );

        $this->assertCount(3, $card->getEmployees());
        $this->assertSame(1, $card->getEmployee(0)->getDay());
        $this->assertSame(2, $card->getEmployee(1)->getDay());
        $this->assertSame(3, $card->getEmployee(2)->getDay());
    }

    public function test_holiday_work_time_submit(): void
    {
        $card = WTO::make()
            ->setBranchCode('0')
            ->setComments('holiday leave')
            ->addEmployee(
                WTOEmployee::make()
                    ->setTin('999999999')
                    ->setFirstName('ΤΕΣΤ')
                    ->setLastName('ΤΕΣΤ')
                    ->setDate('24/04/2022')
                    ->addAnalytics(
                        WTOAnalytics::make()
                            ->setType('ΑΔ.ΚΑΝ')
                            ->setFromTime('')
                            ->setToTime('')
                    )
            );

        $wto = new HolidayWorkTime('test-access-token');
        $wto->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wto->handle($card);

        $this->assertTrue($wto->isSuccessful());
    }

    public function test_holiday_work_time_schema(): void
    {
        $workCard = new HolidayWorkTime("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'wto-schema.json'));
        $workCard->schema();

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_holiday_work_time_pdf(): void
    {
        $workCard = new HolidayWorkTime("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'pdf.txt'));
        $workCard->pdf("ΕΥΣ92", 19800410);

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_holiday_work_time_with_hourly_leave(): void
    {
        $card = WTO::make()
            ->setBranchCode('0')
            ->setComments('hourly leave')
            ->addEmployee(
                WTOEmployee::make()
                    ->setTin('999999999')
                    ->setFirstName('ΤΕΣΤ')
                    ->setLastName('ΤΕΣΤ')
                    ->setDate('24/04/2022')
                    ->addAnalytics(
                        WTOAnalytics::make()
                            ->setType('ΩΑ.ΦΠ')
                            ->setFromTime('09:00')
                            ->setToTime('12:00')
                    )
            );

        $wto = new HolidayWorkTime('test-access-token');
        $wto->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wto->handle($card);

        $this->assertTrue($wto->isSuccessful());
    }
}
