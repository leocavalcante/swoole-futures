<?php declare(strict_types=1);

namespace Futures\Test;

use PHPUnit\Framework\TestCase;
use function Co\run;
use function Futures\async;

class FutureTest extends TestCase
{
    public function testAwait()
    {
        run(function () {
            $future = async(fn(): string => 'foo');
            $this->assertSame('foo', $future->await());
        });
    }
}
