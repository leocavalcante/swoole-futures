<?php declare(strict_types=1);

namespace Acme;

use Swoole\Coroutine as Co;

require_once __DIR__ . '/../vendor/autoload.php';

Co\run(function () {
//    $fut = async(fn() => 2)
//        ->then(fn(int $i) => async(fn() => $i + 3))
//        ->then(fn(int $i) => async(fn() => $i * 4))
//        ->then(fn(int $i) => async(fn() => $i - 5));
//
//    echo $fut->await();
});