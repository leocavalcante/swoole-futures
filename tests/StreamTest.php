<?php declare(strict_types=1);

namespace Futures\Test;

use Co;
use Futures;
use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{
    public function testStream()
    {
        Co\run(function () {
            Futures\stream()
                ->listen(fn($event) => $this->assertSame('foo', $event))
                ->sink('foo');
        });
    }
}
