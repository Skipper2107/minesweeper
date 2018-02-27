<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 1/12/18
 * Time: 9:48 AM
 */

namespace Skipper\Mines\Minesweeper\Decorator;


use Skipper\Mines\Minesweeper\Field;

interface FieldRenderer
{
    public function render(Field $field);
}