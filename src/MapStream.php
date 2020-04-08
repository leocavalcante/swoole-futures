<?php declare(strict_types=1);

namespace Futures;

class MapStream implements StreamInterface
{
    use MapStreamTrait;
    use FilterStreamTrait;

    private StreamInterface $stream;
    private $map;

    public function __construct(StreamInterface $stream, callable $map)
    {
        $this->stream = $stream;
        $this->map = $map;
    }

    public function listen(callable $callback): self
    {
        $this->stream->listen(function ($event) use ($callback): void {
            $callback(($this->map)($event));
        });

        return $this;
    }

    public function sink($event): self
    {
        $this->stream->sink($event);
        return $this;
    }
}