<?php declare(strict_types=1);

namespace Futures;

/**
 * @template T
 */
interface FutureInterface
{
    /**
     * @return mixed
     * @psalm-return T
     */
    public function await();

    /**
     * @return $this
     * @psalm-return FutureInterface<T>
     */
    public function ref(): self;
}