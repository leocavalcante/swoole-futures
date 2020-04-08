<?php declare(strict_types=1);

namespace Futures;

/**
 * @template T1
 * @template T2
 */
trait MapStreamTrait
{
    /**
     * @param callable(T1):T2 $callback
     * @return MapStream
     * @psalm-return MapStream<T1, T2>
     */
    public function map(callable $callback): MapStream
    {
        return new MapStream($this, $callback);
    }
}