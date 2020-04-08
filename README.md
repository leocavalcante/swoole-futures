# Swoole Futures

![https://github.com/leocavalcante/swoole-futures/actions?query=workflow%3ACI](https://github.com/leocavalcante/swoole-futures/workflows/CI/badge.svg)
![https://shepherd.dev/github/leocavalcante/swoole-futures](https://shepherd.dev/github/leocavalcante/swoole-futures/coverage.svg)

⏳ Futures, Streams & Async/Await for PHP's [Swoole](https://www.swoole.co.uk/) asynchronous run-time.

> Inspired by [futures Crate](https://crates.io/crates/futures) for Rust's Tokio asynchronous run-time.

It's on top of [Swoole's coroutines system](https://www.swoole.co.uk/coroutine) there is no special wizardry, just sugar.

## Install

```bash
composer require leocavalcante/swoole-futures
```

## Usage

* [Basic / Hello, world!](#async--await)
* [Join](#join)
* [Race / Select](#race)
* [Async map](#async-map)
* [Then](#then)
* [Stream](#stream)

### Async / await

Creates and awaits for asynchronous computations in an alternative style than Swoole's coroutines. 

```php
$future = Futures\async(fn() => 1);
$result = $future->await(); // 1
```

Futures are lazy, it only runs when you call `await`. 

### Join

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
This takes 3 seconds, not 9, Futures runs concurrently! (Order isn't guaranteed)

### Race

Returns the result of the first finished Future.

```php
use Swoole\Coroutine\Http\Client;

$site1 = Futures\async(function() {
    $client = new Client('www.google.com', 443, true);
    $client->get('/');
    return $client->body;
});

$site2 = Futures\async(function() {
    $client = new Client('www.swoole.co.uk', 443, true);
    $client->get('/');
    return $client->body;
});

$site3 = Futures\async(function() {
    $client = new Client('leocavalcante.dev', 443, true);
    $client->get('/');
    return $client->body;
});

$first_to_load = Futures\race([$site1, $site2, $site3]);

echo $first_to_load;
```

And there is a `Futures\select` alias.

### Async map

Maps an array into a list of Futures where which item runs concurrently.

```php
$list = [1, 2, 3];
$multiply = fn(int $a) => fn(int $b) => $a * $b;
$double = $multiply(2);

$doubles = Futures\join(Futures\async_map($list, $double))->await();

print_r($doubles);
```

### Then

Sequences a series of steps for a Future, is the serial analog for `join`:

```php
use function Futures\async;

$future = async(fn() => 2)
    ->then(fn(int $i) => async(fn() => $i + 3))
    ->then(fn(int $i) => async(fn() => $i * 4))
    ->then(fn(int $i) => async(fn() => $i - 5));

echo $future->await(); // 15
```

### Stream

Streams values/events from `sink` to `listen` with between operations.

```php
$stream = Futures\stream()
    ->map(fn($val) => $val + 1)
    ->filter(fn($val) => $val % 2 === 0)
    ->map(fn($val) => $val * 2)
    ->listen(fn($val) => print("$val\n")); // 4 8 12 16 20

foreach (range(0, 9) as $n) {
    $stream->sink($n);
}
```

---

MIT &copy; 2020
