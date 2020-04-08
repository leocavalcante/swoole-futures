<?php declare(strict_types=1);

namespace Futures;

/**
 * @template T
 */
interface StreamInterface
{
    /**
     * @param callable(T):void $callback
     * @return $this
     */
    public function listen(callable $callback): self;

    /**
     * @param mixed $event
     * @psalm-param T $event
     * @return $this
     */
    public function sink($event): self;
}