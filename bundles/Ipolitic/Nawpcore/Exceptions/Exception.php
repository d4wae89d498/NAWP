<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/14/2018
 * Time: 1:51 PM
 */

namespace App\Ipolitic\Nawpcore\Exceptions;

class Exception extends \Exception
{
    public static function catch(\Throwable $e): string
    {
        while (@ob_end_flush()) {
            sleep(0);
        }
        $log = "<div class='error_main'>";
        $log .= "<h1><small>\\ IPOLITIC \\ NAWP</small> :: Exception ! </h1>";
        $log .= "<br /><b>Error Time :</b>" . date('Y-m-d H:i:s A');
        $log .= "<br /><b>Error Code :</b>" . $e->getCode();
        $log .= "<br /><b>Error Message :</b>" . $e->getMessage();
        $log .= "<br /><b>Error File :</b>" . $e->getFile();
        $log .= "<br /><b>Error File Line :</b>" . $e->getLine();
        $log .= "<br /><b>Error Trace :</b><br />" . preg_replace("/\n/", '<br>', $e->getTraceAsString());
        $log .= "</div>";
        return $log;
    }

    /**
     * @param string $env
     * @throws \Exception
     */
    public static function checkRequireEnv(string $env): void
    {
        if (!isset($_ENV[$env])) {
            throw new \Exception("configs/.env file does not contain definition for the name : " . $env);
        }
        return;
    }
}
