<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 2/27/18
 * Time: 2:12 PM
 */

namespace Skipper\Mines;


use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use Skipper\Mines\Minesweeper\Field;

class ConnectionPool
{
    /** @var \SplObjectStorage $connectionStorage */
    protected $connectionStorage;
    /** @var LoopInterface $loop */
    protected $loop;
    /** @var ConnectionRouter $router */
    protected $router;

    public function __construct(LoopInterface $loop, ConnectionRouter $router)
    {
        $this->connectionStorage = new \SplObjectStorage();
        $this->loop = $loop;
        $this->router = $router;
    }

    public function addConnection(ConnectionInterface $connection, Field $field)
    {
        $connection->on('data', function ($data) use ($connection, $field) {
            $data = trim($data);
            if (empty($data)) {
                return;
            }
            try {
                $this->router->onData($connection, $field, $data);
            } catch (\Exception $exception) {
                $this->router->onError($connection, $exception, $field);
            }
        });

        $connection->on('close', function () use ($connection, $field) {
            $this->connectionStorage->detach($connection);
            try {
                $this->router->onClose($connection, $field);
            } catch (\Exception $exception) {
                $this->router->onError($connection, $exception, $field);
            }
        });

        $connection->on('error', function (\Exception $exception) use ($connection, $field) {
            $connection->write('Error: ' . $exception->getMessage());
        });

        $this->connectionStorage->attach($connection);
    }
}