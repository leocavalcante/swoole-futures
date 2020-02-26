<?php declare(strict_types=1);

namespace Acme;

use Swoole\Coroutine as Co;
use function Futures\async;

require_once __DIR__ . '/../vendor/autoload.php';

Co\run(function () {
    $n1 = async(fn() => [Co::sleep(3), rand(1, 100)]);
    $n2 = async(fn() => [Co::sleep(2), rand(1, 100)]);
    $n3 = async(fn() => [Co::sleep(1), rand(1, 100)]);


});