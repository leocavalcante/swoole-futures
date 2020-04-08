<?php declare(strict_types=1);

namespace Futures;

use Co\Channel;

class Stream implements StreamInterface
{
    use MapStreamTrait;
    use FilterStreamTrait;

    private Channel $channel;

    public function __construct()
    {
        $this->channel = new Channel();
    }

    public function listen(callable $callback): self
    {
        go(function () use ($callback) {
            while (true) {
                $callback($this->channel->pop());
            }
        });

        return $this;
    }

    public function sink($event): self
    {
        $this->channel->push($event);
        return $this;
    }
}