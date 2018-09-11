<?php

namespace App\iPolitic\NawpCore\Components;

class Hsptp {

    public const A = -50;
    public const B = -300;
    public const C = 2;

    public function f(float $x): float {
        return self::A * $x * $x + self::B * $x + self::C;
    }

    public function racines($img) {
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
            // throw new \Exception("Complexes solutions are still not supported, please make sur your polynome verify -b/2a >= 0");
            return [0];
        }
    }

    public function encrypt($string) {
        $arr = [];
        $characters = str_split($string);
        foreach ($characters as $char) {
            $int = ord($char);
            $arr[] = $this->f($int);
        }
        return base64_encode(serialize($arr));
    }

    public function decrypt($string) {
        $arr = unserialize(base64_decode($string));
        $str = "";
        foreach ($arr as $k => $v) {
            $str .= chr($this->racines($v)[0]);
        }
        return $str;
    }

    public function strongEncrypt($str, $loopCount = 3) {
        if ($loopCount > 0) {
            $generatedString = $this->encrypt($str);
            return $this->strongEncrypt($generatedString, --$loopCount);
        } else {
            return $str;
        }
    }

    public function strongDecrypt($str, $loopCount = 3) {
        if ($loopCount > 0) {
            $generatedString = $this->decrypt($str);
            return  $this->strongDecrypt($generatedString, --$loopCount);
        } else {
            return $str;
        }
    }
}