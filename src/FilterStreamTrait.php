<?php declare(strict_types=1);

namespace Futures;

trait FilterStreamTrait
{
    public function filter(callable $callback): FilterStream
    {
        return new FilterStream($this, $callback);
    }
}