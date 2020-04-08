<?php declare(strict_types=1);

namespace Futures;

/**
 * @template T1
 * @template T2
 * @template T3
 */
trait ThenFutureTrait
{
    /**
     * @param callable(T2): FutureInterface<T3> $computation
     * @return ThenFuture
     * @psalm-return ThenFuture<T1, T2, T3>
     */
    public function then(callable $computation): ThenFuture
    {
        return new ThenFuture($this->ref(), $computation);
    }
}