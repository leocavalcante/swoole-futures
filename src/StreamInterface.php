<?php declare(strict_types=1);

namespace Futures;

interface StreamInterface
{
    public function listen(callable $callback): self;

    public function sink($event): self;
}