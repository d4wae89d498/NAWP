<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Controllers;

use App\iPolitic\NawpCore\Controller;
use App\iPolitic\NawpCore\Kernel;

$registration = function() { Kernel::$controllerCollection->append( new Sample () );};
class Sample extends Controller
{
    public $methods = [
        // index
        [
            "method" => "home",
            "type" => "http",
            "router" => ["*", "/:page"],
            "priority" => 0
        ],
        // 404
        [
            "method" => "notFound",
            "type" => "http",
            "router" => ["*", "*"],
            "priority" => -1
        ],
    ];

    public function home(string &$httpResponse, $args = []): bool {
        $httpResponse = "HOMMME";
        return false;
    }
    
    public function notFound(string &$httpResponse, $args = []): bool {
        $httpResponse = "404";
        return true;
    }
}
$registration();
