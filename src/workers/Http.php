<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */
namespace App\Workers;

class Http
{
    public function __construct()
    {
        require_once join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "vendor", "autoload.php"]);
        echo "Starting HTTP Worker...";
        echo "\n";
    }
}
// instanciate the file if was launched using a terminal
(isset($argv) && isset($argv[0]) ? new Http() : null);