<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 1/11/18
 * Time: 5:53 PM
 */

namespace Skipper\Mines\Minesweeper\Cells;


use Skipper\Mines\Minesweeper\Exceptions\LogicException;

trait ManageState
{
    /** @var int $state */
    protected $state = Cell::STATE_CLOSED;

    /**
     * @throws LogicException
     */
    public function mark(): void
    {
        if ($this->state === Cell::STATE_OPENED) {
            throw new LogicException();
        }
        $this->state = $this->state === Cell::STATE_MARKED ? Cell::STATE_CLOSED : Cell::STATE_MARKED;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param int $state
     * @throws LogicException
     */
    public function setState(int $state): void
    {
        if (!in_array($state, [Cell::STATE_MARKED, Cell::STATE_OPENED, Cell::STATE_CLOSED])) {
            throw new LogicException();
        }
        $this->state = $state;
    }
}