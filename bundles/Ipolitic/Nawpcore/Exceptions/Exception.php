<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/14/2018
 * Time: 1:51 PM
 */

namespace App\Ipolitic\Nawpcore\Exceptions;

class Exception extends \Exception
{
    /**
     * @param string $env
     * @throws \Exception
     */
    public static function checkRequireEnv(string $env): void
    {
        if (!isset($_ENV[$env])) {
            throw new \Exception("configs/.env file does not contain definition for the name : " . $env);
        }
        return;
    }
}
