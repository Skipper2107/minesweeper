<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 1/11/18
 * Time: 5:32 PM
 */

namespace Skipper\Mines\Minesweeper;


use Skipper\Mines\Minesweeper\Cells\BombCell;
use Skipper\Mines\Minesweeper\Cells\Cell;
use Skipper\Mines\Minesweeper\Cells\NonLethalCell;
use Skipper\Mines\Minesweeper\Exceptions\LogicException;

class Field
{
    /** @var Cell[][] $cells */
    protected $cells;
    /** @var int $bombs */
    protected $bombs = 0;

    /**
     * @param int $height
     * @param int $width
     * @param int $bombs
     * @return Field
     * @throws LogicException
     */
    public static function generate(int $height = 16, int $width = 30, int $bombs = 99): Field
    {
        if ($height * $width <= $bombs) {
            throw new LogicException();
        }
        $field = new static();
        $field->bombs = $bombs;
        for ($i = 0; $i < $height; $i++) {
            for ($j = 0; $j < $width; $j++) {
                $field->cells[$i][$j] = new NonLethalCell($i, $j);
            }
        }
        while ($bombs > 0) {
            $row = rand(0, $height - 1);
            $column = rand(0, $width - 1);
            if ($field->cells[$row][$column] instanceof BombCell) {
                continue;
            }
            $field->cells[$row][$column] = new BombCell($row, $column);
            foreach ($field->getNeighbors($row, $column) as $neighbor) {
                if ($neighbor instanceof NonLethalCell) {
                    $neighbor->incrementMines();
                }
            }
            $bombs--;
        }
        return $field;
    }

    /**
     * @param int $row
     * @param int $column
     * @return \Generator
     * @throws LogicException
     */
    public function getNeighbors(int $row, int $column): \Generator
    {
        if (!isset($this->cells[$row][$column])) {
            throw new LogicException();
        }
        for ($i = $row - 1; $i < $row + 2; $i++) {
            for ($j = $column - 1; $j < $column + 2; $j++) {
                if (
                    ($j == $column && $i == $row)
                    || !isset($this->cells[$i][$j])
                    || $this->getCell($i, $j)->getState() !== Cell::STATE_CLOSED
                ) {
                    continue;
                }
                yield $this->cells[$i][$j];
            }
        }
    }

    /**
     * @param int $row
     * @param int $column
     * @return Cell
     * @throws LogicException
     */
    protected function getCell(int $row, int $column): Cell
    {
        if (!isset($this->cells[$row][$column])) {
            throw new LogicException();
        }
        return $this->cells[$row][$column];
    }

    /**
     * @param int $row
     * @param int $column
     * @throws LogicException
     */
    public function open(int $row, int $column)
    {
        $this->getCell($row, $column)->open($this);
    }

    /**
     * @param int $row
     * @param int $column
     * @throws LogicException
     */
    public function mark(int $row, int $column)
    {
        $this->getCell($row, $column)->mark();
    }

    /**
     * @param int $row
     * @param int $column
     * @throws LogicException
     */
    public function openNeighbors(int $row, int $column)
    {
        $cell = $this->getCell($row, $column);
        if ($cell->getState() !== Cell::STATE_OPENED) {
            throw new LogicException();
        }
        /** @var Cell $neighbor */
        foreach ($this->getNeighbors($row, $column) as $neighbor) {
            $neighbor->open($this);
        }
    }

    /**
     * @return Cells\Cell[][]
     */
    public function getCells()
    {
        return $this->cells;
    }
}