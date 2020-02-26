# Swoole Futures

⏳ Futures + async/await for PHP's Swoole concurrency run-time.

```bash
composer require leocavalcante/swoole-futures
```

## Async / Await

Creates and awaits asynchronous computations in an alternative way from Swoole's coroutines. 

```php
$future = Futures\async(fn() => /* Async Computation */);
$result = $future->await();
```

## Join

Joins a list of Futures into a single Future that awaits for a list of results.

```php
$slow_rand = function (): int {
    Co::sleep(3);
    return rand(1, 100);
};

$n1 = Futures\async($slow_rand);
$n2 = Futures\async($slow_rand);
$n3 = Futures\async($slow_rand);

$n = Futures\join([$n1, $n2, $n3]);

print_r($n->await());
```
This takes 3 seconds, not 9, Futures runs in parallel! (Order isn't guaranteed)