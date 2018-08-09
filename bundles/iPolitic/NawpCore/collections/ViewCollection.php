<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/7/2018
 * Time: 7:43 PM
 */

namespace App\iPolitic\NawpCore\Collections;

use App\iPolitic\NawpCore\Components\{Collection};

/**
 * Class ViewCollection
 * Contains an array of views
 * @package App\iPolitic\NawpCore\Collections
 */
class ViewCollection extends Collection
{
    /**
     * ControllerCollection constructor.
     * @param array $input
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct(array $input = [], int $flags = 0, string $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);
    }
}