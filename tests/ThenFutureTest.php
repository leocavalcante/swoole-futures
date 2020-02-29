<?php declare(strict_types=1);

namespace Futures\Test;

use PHPUnit\Framework\TestCase;
use function Co\run;
use function Futures\async;

class ThenFutureTest extends TestCase
{
    public function testAwait()
    {
        run(function () {
            $future = async(fn() => 2)
                ->then(fn(int $i) => async(fn() => $i + 3))
                ->then(fn(int $i) => async(fn() => $i * 4))
                ->then(fn(int $i) => async(fn() => $i - 5));

            $this->assertSame(15, $future->await());
        });
    }
}
