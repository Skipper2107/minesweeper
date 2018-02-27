<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 1/11/18
 * Time: 5:33 PM
 */

namespace Skipper\Mines\Minesweeper\Cells;


use Skipper\Mines\Minesweeper\Field;

interface Cell
{

    const STATE_CLOSED = 0;
    const STATE_OPENED = 1;
    const STATE_MARKED = 2;

    /**
     * @param Field $field
     */
    public function open(Field $field): void;

    public function mark(): void;

    /**
     * @return int
     */
    public function getState(): int;

    /**
     * @param int $state
     */
    public function setState(int $state): void;
}