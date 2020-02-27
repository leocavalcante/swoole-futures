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

/**
 * @param Future[] $futures
 * @return mixed
 */
function race(array $futures)
{
    $len = sizeof($futures);
    $chan = new Channel($len);

    foreach ($futures as $f) {
        go(fn() => $chan->push($f->await()));
    }

    return $chan->pop();
}

/**
 * @param array $arr
 * @param callable $callback
 * @return Future[]
 */
function async_map(array $arr, callable $callback): array
{
    return array_map(fn($item): Future => async(fn() => $callback($item)), $arr);
}