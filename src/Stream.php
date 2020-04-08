<?php declare(strict_types=1);

namespace Futures;

use Swoole\Coroutine\Channel;

/**
 * @template T
 * @implements StreamInterface<T>
 */
class Stream implements StreamInterface
{
    /** @use MapStreamTrait<T, mixed> */
    use MapStreamTrait;
    /** @use FilterStreamTrait<T> */
    use FilterStreamTrait;

    private Channel $channel;

    public function __construct()
    {
        $this->channel = new Channel();
    }

    /**
     * @param callable(T):void $callback
     * @return $this
     */
    public function listen(callable $callback): self
    {
        go(function () use ($callback) {
            while (true) {
                /** @psalm-var T $event */
                $event = $this->channel->pop();
                $callback($event);
            }
        });

        return $this;
    }

    /**
     * @param mixed $event
     * @psalm-param T $event
     * @return $this
     */
    public function sink($event): self
    {
        $this->channel->push($event);
        return $this;
    }
}