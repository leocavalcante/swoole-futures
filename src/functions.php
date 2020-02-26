<?php declare(strict_types=1);

namespace Futures;

function async(callable $computation): Future
{
    return new Future($computation);
}