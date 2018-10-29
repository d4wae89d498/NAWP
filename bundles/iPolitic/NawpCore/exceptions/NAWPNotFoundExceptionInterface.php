<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/27/2018
 * Time: 7:28 PM
 */

namespace App\iPolitic\NawpCore\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class NAWPNotFoundExceptionInterface
 * @package App\iPolitic\NawpCore\exceptions
 */
class NAWPNotFoundExceptionInterface extends Exception implements NotFoundExceptionInterface
{
}
