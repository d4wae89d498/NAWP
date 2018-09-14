<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/14/2018
 * Time: 1:51 PM
 */

namespace App\iPolitic\NawpCore\Components;

class Exception
{
    public static function catch(\Exception $e): string
    {
        $log = "<div class='error_main'>";
        $log .= "<h1><small>\\ IPOLITIC \\ NAWP</small> :: Exception ! </h1>";
        $log .= "<br /><b>Error Time :</b>" . date('Y-m-d H:i:s A');
        $log .= "<br /><b>Error Code :</b>" . $e->getCode();
        $log .= "<br /><b>Error Message :</b>" . $e->getMessage();
        $log .= "<br /><b>Error File :</b>" . $e->getFile();
        $log .= "<br /><b>Error File Line :</b>" . $e->getLine();
        $log .= "<br /><b>Error Trace :</b><br />" . preg_replace(
                "/\n/",
                '<br>',
                $e->getTraceAsString()
            );
        $log .= "</div>";
        return $log;
    }
}