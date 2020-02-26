<?php declare(strict_types=1);

namespace Futures;

use Swoole\Coroutine\Channel;

/**
 * @template T
 */
class Future
{
    /** @var callable(...mixed): T */
    private $computation;
    private Channel $channel;

    /**
     * @param callable(...mixed): T $computation
     */
    public function __construct(callable $computation)
    {
        $this->computation = $computation;
        $this->channel = new Channel(1);
    }

    /**
     * @return T
     */
    public function await()
    {
        go(fn() => $this->channel->push(($this->computation)()));
        return $this->channel->pop();
    }
}