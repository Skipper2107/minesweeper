<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 1/12/18
 * Time: 9:48 AM
 */

namespace Skipper\Mines\Minesweeper\Decorator;


use Skipper\Mines\Minesweeper\Cells\Cell;
use Skipper\Mines\Minesweeper\Cells\NonLethalCell;
use Skipper\Mines\Minesweeper\Field;

class JsonRenderer implements FieldRenderer
{
    public function render(Field $field)
    {
        $cells = $field->getCells();
        $result = [];
        /** @var $columns Cell[]  */
        foreach ($cells as $row => $columns) {
            foreach ($columns as $column => $cell) {
                $result[$row][$column] = [
                    'row' => $row,
                    'column' => $column,
                    'state' => $cell->getState(),
                ];
                if ($cell instanceof NonLethalCell && $cell->getState() === Cell::STATE_OPENED) {
                    $result[$row][$column]['mines'] = $cell->getMinesAround();
                }
            }
        }
        return json_encode($result);
    }
}