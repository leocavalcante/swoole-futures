<?php declare(strict_types=1);

namespace Futures;

trait MapStreamTrait
{
    public function map(callable $callback): MapStream
    {
        return new MapStream($this, $callback);
    }
}