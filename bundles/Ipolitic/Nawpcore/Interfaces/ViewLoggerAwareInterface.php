<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 6:02 PM
 */

namespace App\Ipolitic\Nawpcore\Interfaces;

use App\Ipolitic\Nawpcore\Components\ViewLogger;

interface ViewLoggerAwareInterface
{
    public function setViewLogger(ViewLogger &$viewLogger);
}
