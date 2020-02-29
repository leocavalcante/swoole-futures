<?php declare(strict_types=1);

namespace Futures;

/**
 * @template T1
 * @template T2
 * @template T3
 * @implements FutureInterface<T1>
 */
final class ThenFuture implements FutureInterface
{
    /** @use Then<T1, T2, T3> */
    use Then;

    /** @psalm-var FutureInterface<T2> */
    private FutureInterface $future;
    /** @var callable(T2): FutureInterface<T3> */
    private $computation;

    /**
     * @param FutureInterface $future
     * @psalm-param FutureInterface<T2> $future
     * @param callable(T2): FutureInterface<T3> $computation
     */
    public function __construct(FutureInterface $future, callable $computation)
    {
        $this->future = $future;
        $this->computation = $computation;
    }

    /**
     * @return mixed
     * @psalm-return T1
     */
    public function await()
    {
        /**
         * @var mixed $future_result
         * @psalm-var T2 $future_result
         */
        $future_result = $this->future->await();

        /**
         * @var FutureInterface $computation_result
         * @psalm-var FutureInterface<T3> $computation_result
         */
        $computation_result = ($this->computation)($future_result);

        /**
         * @psalm-var T3
         */
        return $computation_result->await();
    }

    public function ref(): FutureInterface
    {
        return $this;
    }
}