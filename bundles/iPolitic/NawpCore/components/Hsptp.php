<?php

namespace App\iPolitic\NawpCore\Components;

use App\iPolitic\NawpCore\Kernel;
use phpseclib\Crypt\RSA;

/**
 * The HSPTP encryption class
 * Class Hsptp
 * @package App\iPolitic\NawpCore\Components
 */
class Hsptp {
    public const USE_COMPRESSION = false;
    public const USE_RSA = false;
    public const A = -50;
    public const B = -300;
    public const C = 2;

    public function f(float $x): float {
        return self::A * $x * $x + self::B * $x + self::C;
    }

    public function racines(float $img) {
        $delta = self::B * self::B - (4 * self::A * (self::C - $img));
        //echo "delta : " ;
        //var_dump($delta);
        if ($delta > 0) {
            return [
                (-self::B - sqrt($delta)) / (2 * self::A),
                (-self::B + sqrt($delta)) / (2 * self::A)
            ];
        } elseif ($delta === 0) {
            return [
                (-self::B - sqrt(self::A * 2) ),
            ];
        } else {
            return [0];
        }
    }

    public function encrypt(string $string) {
        $rsa = new RSA();
        if (self::USE_RSA) {
            $rsaKeys = Kernel::getKernel()->rsaKeys;
            $rsa->load($rsaKeys['publickey']);
        }
        $arr = [];
        $characters = str_split($string);
        foreach ($characters as $char) {
            $int = ord($char);
            $arr[] = (self::USE_RSA ? $rsa->encrypt($this->f($int)) : $this->f($int));
        }
        return self::USE_COMPRESSION ? Utils::compress(base64_encode(serialize($arr))) : base64_encode(serialize($arr));
    }

    public function decrypt(string $string) {
        $string = self::USE_COMPRESSION ? Utils::decompress($string) : $string;
        if (self::USE_RSA) {
            $rsa = new RSA();
            $rsaKeys = Kernel::getKernel()->rsaKeys;
            $rsa->load($rsaKeys['privatekey']);
        }
        $arr = unserialize(base64_decode($string));
        $str = "";
        foreach ($arr as $k => $v) {
            $str .= chr($this->racines((float)(self::USE_RSA ? $rsa->decrypt($v) : $v))[0]);
        }
        return $str;
    }
}