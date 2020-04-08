<?php declare(strict_types=1);

namespace Futures;

/**
 * @template T1
 * @template T2
 * @implements StreamInterface<T1>
 */
class MapStream implements StreamInterface
{
    /** @use MapStreamTrait<T1, T2> */
    use MapStreamTrait;
    /** @use FilterStreamTrait<T1> */
    use FilterStreamTrait;


    private StreamInterface $stream;
    /** @var callable(T2):T1 */
    private $map;

    /**
     * @param StreamInterface $stream
     * @psalm-param StreamInterface<T2> $stream
     * @param callable(T2):T1 $map
     */
    public function __construct(StreamInterface $stream, callable $map)
    {
        $this->stream = $stream;
        $this->map = $map;
    }

    /**
     * @param callable(T1):void $callback
     * @return $this
     */
    public function listen(callable $callback): self
    {
        $this->stream->listen(
            /** @psalm-param T1 $event */
            function ($event) use ($callback): void {
                $callback(($this->map)($event));
            }
        );

        return $this;
    }

    /**
     * @param mixed $event
     * @psalm-param T1 $event
     * @return $this
     */
    public function sink($event): self
    {
        $this->stream->sink($event);
        return $this;
    }
}