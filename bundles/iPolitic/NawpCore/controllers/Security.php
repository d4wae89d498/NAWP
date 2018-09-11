<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/11/2018
 * Time: 9:15 PM
 */

namespace App\iPolitic\NawpCore\controllers;

use phpseclib\Crypt\RSA;
use App\iPolitic\NawpCore\Components\Controller;
use App\iPolitic\NawpCore\Components\Session;
use App\iPolitic\NawpCore\Interfaces\ControllerInterface;

class Security extends Controller implements ControllerInterface
{
    public function getMethods(): array
    {
    }

    /**
     * This middleware will store in session some generated RSA keys
     * @param string $httpResponse
     * @param array $args
     * @param string $requestType
     * @return bool
     */
    public function RSA(string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool
    {

        if (!Session::isset("rsa_keys")) {
            $rsa = new RSA();
            define('CRYPT_RSA_EXPONENT', 65537);
            define('CRYPT_RSA_SMALLEST_PRIME', 64); // makes it so multi-prime RSA is used
            $keys = $rsa->createKey(1024);
            Session::set("rsa_keys", $keys);
        }

    }
}

