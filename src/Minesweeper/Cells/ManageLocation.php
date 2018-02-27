<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 1/12/18
 * Time: 10:12 AM
 */

namespace Skipper\Mines\Minesweeper\Cells;


trait ManageLocation
{
    /** @var $row int */
    protected $row;
    /** @var int $column */
    protected $column;

    /**
     * @param int $row
     * @param int $column
     */
    protected function setLocation(int $row, int $column)
    {
        $this->row = $row;
        $this->column = $column;
    }
}