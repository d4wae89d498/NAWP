<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/9/2018
 * Time: 1:11 PM
 */

namespace App\iPolitic\NawpCore\Collections;

use App\iPolitic\NawpCore\Components\{Collection};

/**
 * Class ModelCollection
 * A model collection contains *surprise* an array of Models
 * @package App\iPolitic\NawpCore\Collections
 */
class ModelCollection extends Collection
{
    /**
     * ModelCollection constructor.
     * @param array $input
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct(array $input = array(), int $flags = 0, string $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);
    }
}