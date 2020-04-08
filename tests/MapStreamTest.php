<?php declare(strict_types=1);

namespace Futures\Test;

use Co;
use Futures;
use PHPUnit\Framework\TestCase;

class MapStreamTest extends TestCase
{
    public function testMapStream()
    {
        Co\run(function () {
            Futures\stream()
                ->map(fn($val) => $val * $val)
                ->listen(fn($val) => $this->assertSame(4, $val))
                ->sink(2);
        });
    }
}
