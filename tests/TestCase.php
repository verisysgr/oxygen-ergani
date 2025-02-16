<?php

namespace Tests;

use DateTime;
use DateTimeInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;
use PHPUnit\Framework\TestCase as BaseCaseAlias;

class TestCase extends BaseCaseAlias
{
    protected function requireFile(string $filename): array
    {
        return require __DIR__.'/responses/'.$filename;
    }

    protected function mockResponse(int $status, string $filename): MockHandler
    {
        // If filename is php file, require it
        if (str_ends_with($filename, '.php')) {
            $body = json_encode($this->requireFile($filename));
        } else {
            $body = file_get_contents(__DIR__.'/responses/'.$filename);
        }

        return new MockHandler([
            new HttpResponse($status, body: $body),
        ]);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    protected function assertDates(DateTimeInterface|string $date1, DateTimeInterface|string $date2): void
    {
        $date1 = $date1 instanceof DateTimeInterface ? $date1 : new DateTime($date1);
        $date2 = $date2 instanceof DateTimeInterface ? $date2 : new DateTime($date2);

        $this->assertEquals($date1, $date2);
    }
}
