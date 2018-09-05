<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/1/2018
 * Time: 12:06 PM
 */

namespace App\iPolitic\NawpCore\components;


class SocketAdapter
{
    /**
     * @var Hsptp
     */
    public $hsptp;

    public function __construct()
    {
        $this->hsptp = new Hsptp();
    }

    /**
     * Will return a crypted $_server representation string
     * @return string
     * @throws \Exception
     */
    public function getCryptedDServer(): string {
        $var = @serialize($_SERVER);
        // if serialisation succeeed
        if ($var !== false) {
            return $this->hsptp->encrypt($var);
        }
        // else serialisation failed
        else {
            throw new \Exception("Serialisation failed");
        }
    }

    /**
     *      * Wil return a decrypted $_server var
     * @param string $cryptedString
     * @return array
     * @throws \Exception
     */
    public function getDecryptedDServer(string $cryptedString ): array {
        $decryptedString = $this->hsptp->decrypt($cryptedString);
        $output = @unserialize($decryptedString);
        // if unserialisation succeed
        if($output !== false) {
            return $output;
        } else {
            throw new \Exception("Unserialisation failed");
        }
    }
}