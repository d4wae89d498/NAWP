<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/30/2018
 * Time: 2:55 PM
 */

namespace App\Ipolitic\Nawpcore\Components;

use App\Ipolitic\Nawpcore\Kernel;

/**
 * Class Middleware
 * @package App\Ipolitic\Nawpcore\Components
 */
class Middleware
{
    public $kernel;
    /**
     * Middleware constructor.
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }
}
