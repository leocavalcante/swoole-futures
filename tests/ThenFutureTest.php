<?php declare(strict_types=1);

namespace Futures\Test;

use Co;
use Futures;
use PHPUnit\Framework\TestCase;

class ThenFutureTest extends TestCase
{
    public function testAwait()
    {
        Co\run(function () {
            $future = Futures\async(fn() => 2)
                ->then(fn(int $i) => Futures\async(fn() => $i + 3))
                ->then(fn(int $i) => Futures\async(fn() => $i * 4))
                ->then(fn(int $i) => Futures\async(fn() => $i - 5));

            $this->assertSame(15, $future->await());
        });
    }
}
