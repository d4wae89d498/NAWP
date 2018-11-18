<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 6:24 PM
 */

namespace App\Ipolitic\Nawpcore\Exceptions;

use Throwable;

class SetViewLoggerNotCalled extends Exception
{
    public function __construct(string $message = "setViewLogger was not called before template rendering.", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
