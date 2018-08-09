<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/9/2018
 * Time: 1:11 PM
 */

namespace App\iPolitic\NawpCore\Components;

/**
 * Class Relation
 * @package App\iPolitic\NawpCore\Components
 */
class Relation extends Collection
{
    /**
     * Relation constructor.
     * @param array $input
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct(array $input = array(), int $flags = 0, string $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);
    }
}