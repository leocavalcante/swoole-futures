<?php declare(strict_types=1);

namespace Futures;

class FilterStream implements StreamInterface
{
    use MapStreamTrait;
    use FilterStreamTrait;

    private StreamInterface $stream;
    /** @var callable */
    private $filter;

    public function __construct(StreamInterface $stream, callable $filter)
    {
        $this->stream = $stream;
        $this->filter = $filter;
    }

    public function listen(callable $callback): self
    {
        $this->stream->listen(function ($event) use ($callback): void {
            if (($this->filter)($event)) {
                $callback($event);
            }
        });

        return $this;
    }

    public function sink($event): self
    {
        $this->stream->sink($event);
        return $this;
    }
}