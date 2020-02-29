<?php declare(strict_types=1);

namespace Futures\Test;

use PHPUnit\Framework\TestCase;
use function Co\run;
use function Futures\async;

class JoinFutureTest extends TestCase
{
    public function testAwait()
    {
        run(function () {
            $future = \Futures\join([
                async(fn(): string => 'foo'),
                async(fn(): string => 'bar'),
                async(fn(): string => 'baz'),
            ]);

            $this->assertSame(['foo', 'bar', 'baz'], $future->await());
        });
    }
}
