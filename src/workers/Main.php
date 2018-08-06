<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */
namespace App\Workers;

use App\iPolitic\NawpCore\Kernel;
use App\iPolitic\NawpCore\NArray;

class Main
{
    public function __construct()
    {
        require_once join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "vendor", "autoload.php"]);
        $kernel = new Kernel();
            // var_dump( join([__DIR__, "..", "..", "src"]))->join(DIRECTORY_SEPARATOR);
        Kernel::loadDir( join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "src"]));
        Kernel::loadDir( join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "bundles"]));
        $response = "";
        var_dump($kernel->handle($response, "http", "test"));
        var_dump($response);
        //var_dump(Kernel::$controllerCollection);

        //Kernel::requireAll(join(DIRECTORY_SEPARATOR, [__DIR__, ".."]));
        //Kernel::requireAll(join(DIRECTORY_SEPARATOR, ["..", "..", "bundles"]));
    /*    echo "Starting Main Worker...";
        echo "Starting Memcache ... ";
        $memcache = new \Memcache;
        $memcache->connect('127.0.0.1', 11211);
        $memcache->set('var_key', 'AAAAAA', MEMCACHE_COMPRESSED, 50);
        echo $memcache->get('var_key');
        echo "\n";
*/

    }
}
// Instanciate the file class if it was launched using a terminal
(isset($argv) && isset($argv[0]) ? new Main() : null);