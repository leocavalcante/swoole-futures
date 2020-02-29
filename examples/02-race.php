<?php declare(strict_types=1);

namespace Acme;

use Swoole\Coroutine\Http\Client;
use function Co\run;
use function Futures\async;
use function Futures\select;

require_once __DIR__ . '/../vendor/autoload.php';

run(function () {
    $site1 = async(function () {
        $client = new Client('www.google.com', 443, true);
        $client->get('/');
        return $client->body;
    });

    $site2 = async(function () {
        $client = new Client('www.swoole.co.uk', 443, true);
        $client->get('/');
        return $client->body;
    });

    $site3 = async(function () {
        $client = new Client('leocavalcante.dev', 443, true);
        $client->get('/');
        return $client->body;
    });

    $first_to_load = select([$site1, $site2, $site3]);

    echo $first_to_load->await();
});
