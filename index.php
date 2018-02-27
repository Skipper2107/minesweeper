<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 1/12/18
 * Time: 11:38 AM
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$di = new \Illuminate\Container\Container();

$config = new \Skipper\Mines\Config(__DIR__);
$di->singleton(\Skipper\Mines\Minesweeper\Decorator\FieldRenderer::class, \Skipper\Mines\Minesweeper\Decorator\JsonRenderer::class);

$loop = \React\EventLoop\Factory::create();
$di->instance(\React\EventLoop\LoopInterface::class, $loop);

$pool = $di->make(\Skipper\Mines\ConnectionPool::class);

$socket = new \React\Socket\Server($config->getListenedUri(), $loop);
$socket->on('connection', function (\React\Socket\ConnectionInterface $connection) use ($pool) {
    $pool->addConnection($connection, \Skipper\Mines\Minesweeper\Field::generate());
});

$loop->run();
