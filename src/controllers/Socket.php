<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Controllers;

use App\iPolitic\NawpCore\Components\ViewLogger;
use App\iPolitic\NawpCore\Components\Controller;
use App\iPolitic\NawpCore\Interfaces\ControllerInterface;
use  App\iPolitic\NawpCore\Components\Session as SupSession;

/**
 * Class Sample
 * @package App\Controllers
 */
class Socket extends Controller implements ControllerInterface
{

    /**
     * Describes controller methods
     * @return array
     */
    public function getMethods(): array { return
        [
            [
                "method"    => "socketMiddleware",
                "router"    => ["*", "*"],
                "priority"  => 9999,
            ],
        ];
    }

    /**
     * The socket middleware
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function socektMiddleware(string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool {
        var_dump($args);
        $httpResponse = "patate";
        //exit();
        return true;
    }

}
