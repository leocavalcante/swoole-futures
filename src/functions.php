<?php declare(strict_types=1);

namespace Futures;

use Co\Channel;

function async(callable $computation): Future
{
    return new Future($computation);
}

/**
 * @param Future[] $futures
 * @return Future
 */
function join(array $futures): Future
{
    return async(function () use ($futures): array {
        $len = sizeof($futures);
        $chan = new Channel($len);
        $results = [];

        foreach ($futures as $f) {
            go(fn() => $chan->push($f->await()));
        }

        for ($i = 0; $i < $len; $i++) {
            $results[] = $chan->pop();
        }

        return $results;
    });
}