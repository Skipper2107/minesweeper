<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 1/11/18
 * Time: 5:54 PM
 */

namespace Skipper\Mines\Minesweeper\Cells;


use Skipper\Mines\Minesweeper\Exceptions\GameOverException;
use Skipper\Mines\Minesweeper\Field;

class BombCell implements Cell
{
    use ManageState;
    use ManageLocation;

    public function __construct(int $row, int $column)
    {
        $this->setLocation($row, $column);
    }

    /**
     * @param Field $field
     * @throws GameOverException
     */
    public function open(Field $field): void
    {
        throw new GameOverException();
    }
}