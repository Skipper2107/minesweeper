<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 2/27/18
 * Time: 2:25 PM
 */

namespace Skipper\Mines;


use React\Socket\ConnectionInterface;
use Skipper\Mines\Minesweeper\Decorator\FieldRenderer;
use Skipper\Mines\Minesweeper\Exceptions\GameOverException;
use Skipper\Mines\Minesweeper\Exceptions\MineException;
use Skipper\Mines\Minesweeper\Field;

class ConnectionRouter
{

    protected $renderer;

    public function __construct(FieldRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param ConnectionInterface $connection
     * @param Field $field
     * @param $data
     * @throws \Skipper\Mines\Minesweeper\Exceptions\LogicException
     * @throws MineException
     */
    public function onData(ConnectionInterface $connection, Field $field, $data)
    {
        preg_match_all('/^\/match (\d{1,2}) (\d{1,2})$/', $data, $markMatch, PREG_SET_ORDER);
        preg_match_all('/^\/open (\d{1,2}) (\d{1,2})$/', $data, $openMatch, PREG_SET_ORDER);
        preg_match_all('/^\/release (\d{1,2}) (\d{1,2})$/', $data, $releaseMatch, PREG_SET_ORDER);
        if (!empty($markMatch)) {
            $field->mark($markMatch[0][1], $markMatch[0][2]);
        } elseif (!empty($openMatch)) {
            $field->open($openMatch[0][1], $openMatch[0][2]);
        } elseif (!empty($releaseMatch)) {
            $field->openNeighbors($releaseMatch[0][2], $releaseMatch[0][2]);
        } else {
            throw new MineException('Input not found');
        }
        $connection->write($this->renderer->render($field) . PHP_EOL);
    }

    /**
     * @param ConnectionInterface $connection
     * @param Field $field
     */
    public function onClose(ConnectionInterface $connection, Field $field)
    {
        $connection->write("\r" . 'Goodbye!');
    }

    public function onError(ConnectionInterface $connection, \Exception $exception, Field $field)
    {
        if ($exception instanceof GameOverException) {
            $connection->write($this->renderer->render($field) . PHP_EOL);
            $connection->write('Game Over, Sorry' . PHP_EOL);
//            $connection->emit('close');
            return;
        }
        $connection->write($exception->getMessage() . PHP_EOL);
    }
}