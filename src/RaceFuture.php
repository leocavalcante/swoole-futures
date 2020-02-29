<?php declare(strict_types=1);

namespace Futures;

use Swoole\Coroutine\Channel;

final class RaceFuture implements FutureInterface
{
    use Then;

    /**
     * @var FutureInterface[]
     */
    private array $futures;
    private Channel $channel;

    /**
     * @param FutureInterface[] $futures
     */
    public function __construct(array $futures)
    {
        $this->futures = $futures;
        $this->channel = new Channel(1);
    }

    public function await()
    {
        foreach ($this->futures as $future) {
            go(
                function () use ($future): void {
                    $this->channel->push($future->await());
                }
            );
        }

        return $this->channel->pop();
    }

    public function ref(): FutureInterface
    {
        return $this;
    }
}