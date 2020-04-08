<?php declare(strict_types=1);

namespace Futures;

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
 * @param FutureInterface[] $futures
 * @return JoinFuture
 */
function join(array $futures): JoinFuture
{
    return new JoinFuture($futures);
}

/**
 * @param FutureInterface[] $futures
 * @return RaceFuture
 */
function race(array $futures)
{
    return new RaceFuture($futures);
}

/**
 * @param FutureInterface[] $futures
 * @return RaceFuture
 */
function select(array $futures)
{
    return new RaceFuture($futures);
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

/**
 * @return Stream
 */
function stream(): Stream
{
    return new Stream();
}
