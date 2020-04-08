<?php declare(strict_types=1);

namespace Futures;

use Swoole\Coroutine\Channel;

final class JoinFuture implements FutureInterface
{
    use ThenFutureTrait;

    /**
     * @var FutureInterface[]
     */
    private array $futures;
    private int $length;
    private Channel $channel;

    /**
     * @param FutureInterface[] $futures
     */
    public function __construct(array $futures)
    {
        $this->futures = $futures;
        $this->length = sizeof($this->futures);
        $this->channel = new Channel($this->length);
    }

    /**
     * @return mixed[]
     */
    public function await(): array
    {
        $results = [];

        foreach ($this->futures as $future) {
            go(
                function () use ($future): void {
                    $this->channel->push($future->await());
                }
            );
        }

        for ($i = 0; $i < $this->length; $i++) {
            /** @var mixed */
            $results[] = $this->channel->pop();
        }

        return $results;
    }

    public function ref(): FutureInterface
    {
        return $this;
    }
}