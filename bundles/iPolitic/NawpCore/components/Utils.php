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

}