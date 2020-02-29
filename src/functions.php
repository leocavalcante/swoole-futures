<?php declare(strict_types=1);

namespace Futures;

use Swoole\Coroutine\Channel;

/**
 * @template T
 * @param callable(): T $computation
 * @return Future
 * @psalm-return Future<T>
 */
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
    return async(static function () use ($futures): array {
        $len = sizeof($futures);
        $chan = new Channel($len);
        $results = [];

        foreach ($futures as $future) {
            go(
                static function () use ($chan, $future): void {
                    $chan->push($future->await());
                }
            );
        }

        for ($i = 0; $i < $len; $i++) {
            /** @var mixed */
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

    foreach ($futures as $future) {
        go(
            static function () use ($chan, $future): void {
                $chan->push($future->await());
            }
        );
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
    return array_map(
        static function ($item) use ($callback): Future {
            return async(
                static function () use ($callback, $item): void {
                    $callback($item);
                }
            );
        },
        $arr
    );
}