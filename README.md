# Swoole Futures

⏳ Futures + async/await for PHP's Swoole concurrency run-time.

## Install

```bash
composer require leocavalcante/swoole-futures
```

## Usage

### Async / await

Creates and awaits for asynchronous computations in an alternative style than Swoole's coroutines. 

```php
$future = Futures\async(fn() => /* Async Computation */);
$result = $future->await();
```

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

# Async map

Maps a array into a list of Futures where which item runs concurrently.

```php
$list = [1, 2, 3];
$multiply = fn(int $a) => fn(int $b) => $a * $b;
$double = $multiply(2);

$doubles = Futures\join(Futures\async_map($list, $double))->await();

print_r($doubles);
```