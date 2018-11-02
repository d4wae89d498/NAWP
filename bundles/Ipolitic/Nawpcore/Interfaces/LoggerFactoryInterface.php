<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 7:11 PM
 */

namespace App\Ipolitic\Nawpcore\Interfaces;

use Psr\Log\LoggerInterface;

interface LoggerFactoryInterface
{
    public function createLogger(): LoggerInterface;
}
