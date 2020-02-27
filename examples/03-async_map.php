<?php declare(strict_types=1);

namespace Acme;

use function Futures\async_map;
use function Swoole\Coroutine\run;

require_once __DIR__ . '/../vendor/autoload.php';

run(function () {
    $list = [1, 2, 3];
    $multiply = fn(int $a) => fn(int $b) => $a * $b;
    $double = $multiply(2);

    $doubles = \Futures\join(async_map($list, $double))->await();

    print_r($doubles);
});