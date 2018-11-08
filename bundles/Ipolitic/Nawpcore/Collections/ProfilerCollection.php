<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */

namespace App\Ipolitic\Nawpcore\Collections;

use App\Ipolitic\Nawpcore\Components\{Collection};
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ControllerCollection
 * Provide storage and match for a controller list
 * @package App\Ipolitic\Nawpcore
 */
class ProfilerCollection extends Collection
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
