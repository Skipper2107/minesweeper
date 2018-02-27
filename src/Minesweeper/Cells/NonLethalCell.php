<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 1/11/18
 * Time: 5:37 PM
 */

namespace Skipper\Mines\Minesweeper\Cells;


use Skipper\Mines\Minesweeper\Field;

class NonLethalCell implements Cell
{
    use ManageState;
    use ManageLocation;

    /** @var int $minesAround */
    protected $minesAround;


    public function __construct(int $row, int $column, int $minesAround = 0)
    {
        $this->minesAround = $minesAround;
        $this->setLocation($row, $column);
    }

    public function incrementMines()
    {
        $this->minesAround++;
    }

    /**
     * @return int
     */
    public function getMinesAround(): int
    {
        return $this->minesAround;
    }

    /**
     * @param Field $field
     * @throws \Skipper\Mines\Minesweeper\Exceptions\LogicException
     */
    public function open(Field $field): void
    {
        if ($this->getState() === Cell::STATE_OPENED) {
            return;
        }
        $this->setState(Cell::STATE_OPENED);
        $neighbors = $field->getNeighbors($this->row, $this->column);
        if ((bool)$this->minesAround) {
            /** @var Cell $neighbor */
            $marked = 0;
            foreach ($neighbors as $neighbor) {
                if ($neighbor->getState() === Cell::STATE_MARKED) {
                    $marked++;
                }
            }
            if ($this->minesAround !== $marked) {
                return;
            }
        }
        /** @var Cell $neighbor */
        foreach ($neighbors as $neighbor) {
            $neighbor->open($field);
        }
    }
}