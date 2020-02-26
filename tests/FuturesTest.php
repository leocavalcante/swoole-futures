<?php declare(strict_types=1);

namespace Futures\Test;

use PHPUnit\Framework\TestCase;
use function Co\run;
use function Futures\async;

class FuturesTest extends TestCase
{
    public function testAwait()
    {
        $future = async(fn(): string => 'test');
        run(fn() => $this->assertSame('test', $future->await()));
    }
}