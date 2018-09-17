<?php
namespace App\iPolitic\NawpCore\Components;

/**
 * All nawp utils.
 *
 * All small function & snipets should go there
 *
 * @version 1.0
 * @author fauss
 */
class Utils
{
    /**
     * Will print a var to the console
     * @param $var
     */
    public static function p($var, bool $exitScript = false): void {
        var_dump($var);
        if($exitScript) {
            exit;
        }
    }
    /**
     * Will return the
     * @param string $input
     * @param string $tag
     * @return mixed
     */
    public static function strInTag(string $input, string $tag): string {
        $matches = [];
        $pattern = "#<".$tag.".*?>([^<]+)</".$tag.">#";
        preg_match_all($pattern, $input, $matches);
        return $matches[0][0];
    }
    /**
     * Will execute the function, and return all rendered data as a string
     * @param mixed $func
     * @return string
     */
    public static function ocb($func): string {
        ob_start();
        call_user_func($func);
        $res = ob_get_contents();
        ob_end_clean();
        return $res;
    }

    /**
     * Will decompress a string
     * @param string $string
     * @return string*
     */
    public static function compress(string $string): string {
        return gzdeflate($string, 9);
    }

    /**
     * Will compress a string
     * @param string $string
     * @return string
     */
    public static function decompress(string $string): string {
        return gzinflate($string);
    }
}