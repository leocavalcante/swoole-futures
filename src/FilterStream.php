<?php declare(strict_types=1);

namespace Futures;

/**
 * @template T
 * @implements StreamInterface<T>
 */
class FilterStream implements StreamInterface
{
    /** @use MapStreamTrait<T, mixed> */
    use MapStreamTrait;
    /** @use FilterStreamTrait<T> */
    use FilterStreamTrait;


    private StreamInterface $stream;
    /** @var callable(T):bool */
    private $filter;

    /**
     * @param StreamInterface $stream
     * @psalm-param StreamInterface<T> $stream
     * @param callable(T):bool $filter
     */
    public function __construct(StreamInterface $stream, callable $filter)
    {
        $this->stream = $stream;
        $this->filter = $filter;
    }

    /**
     * @param callable(T):void $callback
     * @return $this
     */
    public function listen(callable $callback): self
    {
        $this->stream->listen(
            /** @psalm-param T $event */
            function ($event) use ($callback): void {
                if (($this->filter)($event)) {
                    $callback($event);
                }
            }
        );

        return $this;
    }

    /**
     * @param mixed $event
     * @psalm-param T $event
     * @return $this
     */
    public function sink($event): self
    {
        $this->stream->sink($event);
        return $this;
    }
}