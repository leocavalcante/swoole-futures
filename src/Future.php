<?php declare(strict_types=1);

namespace Futures;

use Swoole\Coroutine\Channel;

/**
 * @template T
 */
final class Future implements FutureInterface
{
    use ThenFutureTrait;

    /**
     * @var callable(): T
     */
    private $computation;
    private Channel $channel;

    /**
     * @param callable(): T $computation
     */
    public function __construct(callable $computation)
    {
        $this->computation = $computation;
        $this->channel = new Channel(1);
    }

    /**
     * @return mixed
     * @psalm-return T
     */
    public function await()
    {
        go(
            function (): void {
                $this->channel->push(($this->computation)());
            }
        );

        /** @psalm-var T */
        return $this->channel->pop();
    }

    public function ref(): FutureInterface
    {
        return $this;
    }
}