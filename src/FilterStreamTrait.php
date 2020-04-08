<?php declare(strict_types=1);

namespace Futures;

/**
 * @template T
 */
trait FilterStreamTrait
{
    /**
     * @param callable(T):bool $callback
     * @return FilterStream
     */
    public function filter(callable $callback): FilterStream
    {
        return new FilterStream($this, $callback);
    }
}