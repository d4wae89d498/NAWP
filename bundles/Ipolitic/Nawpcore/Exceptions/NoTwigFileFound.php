<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/27/2018
 * Time: 7:28 PM
 */

namespace App\Ipolitic\Nawpcore\Exceptions;

use Psr\Container\NotFoundExceptionInterface as PsrNotFound;

/**
 * Class NAWPNotFoundExceptionInterface
 * @package App\Ipolitic\Nawpcore\exceptions
 */
class NoTwigFileFound extends Exception implements PsrNotFound
{
    public function __construct(string $path)
    {
        $message = "No twig file was found at : " . $path;
        $code = 0;
        $previous = null;
        parent::__construct($message, $code, $previous);
    }
}
