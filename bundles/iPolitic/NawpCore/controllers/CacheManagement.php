<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/11/2018
 * Time: 9:15 PM
 */

namespace App\iPolitic\NawpCore\controllers;

use App\iPolitic\NawpCore\Components\Controller;
use App\iPolitic\NawpCore\Interfaces\ControllerInterface;

class CacheManagement extends Controller implements ControllerInterface
{
    public function getMethods(): array
    {
    }

    /**
     * Will check for cache expiration
     * @param string $httpResponse
     * @param array $args
     * @param string $requestType
     * @return bool
     */
    public function CheckCacheExpiration(string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool
    {

    }
}