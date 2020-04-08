<?php declare(strict_types=1);

namespace Acme;

use Co;
use Futures;

require_once __DIR__ . '/../vendor/autoload.php';

function main()
{
    $stream = Futures\stream()
        ->map(fn($val) => $val + 1)
        ->filter(fn($val) => $val % 2 === 0)
        ->map(fn($val) => $val * 2)
        ->listen(fn($val) => print("$val\n"));

    foreach (range(0, 9) as $n) {
        $stream->sink($n);
    }
}

Co\run('Acme\main');