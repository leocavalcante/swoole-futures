<?php declare(strict_types=1);

namespace Acme;

use Swoole\Coroutine as Co;
use function Futures\async;

require_once __DIR__ . '/../vendor/autoload.php';

Co\run(function () {
    $slow_rand = function (): int {
        Co::sleep(3);
        return rand(1, 100);
    };

    $n1 = async($slow_rand);
    $n2 = async($slow_rand);
    $n3 = async($slow_rand);

    $n = \Futures\join([$n1, $n2, $n3]);

    print_r($n->await());
});

