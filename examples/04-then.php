<?php declare(strict_types=1);

namespace Acme;

use Swoole\Coroutine as Co;
use function Futures\async;

require_once __DIR__ . '/../vendor/autoload.php';

Co\run(function () {
    $future = async(fn() => 2)
        ->then(fn(int $i) => async(fn() => $i + 3))
        ->then(fn(int $i) => async(fn() => $i * 4))
        ->then(fn(int $i) => async(fn() => $i - 5));

    echo $future->await(); // 15
});