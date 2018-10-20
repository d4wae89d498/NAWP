<?php
namespace App\iPolitic\NawpCore\Components;

/**
 * Class ArrayObject
 * @package App\iPolitic\NawpCore
 */
class Collection extends \ArrayObject
{

    /**
     * Collection constructor.
     * @param array $input
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct($input = array(), int $flags = 0, string $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);
        $this ->setFlags(\ArrayObject::STD_PROP_LIST|\ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * @see dot man at lightning dot hu ¶'s comment http://php.net/manual/fr/class.arrayobject.php
     * @param $func
     * @param $argv
     * @return mixed
     */
    public function __call($func, $argv)
    {
        if (!is_callable($func) || substr($func, 0, 6) !== 'array_') {
            throw new \BadMethodCallException(__CLASS__.'->'.$func);
        }
        return call_user_func_array($func, array_merge(array($this->getArrayCopy()), $argv));
    }
}
