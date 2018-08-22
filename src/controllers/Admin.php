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

/**
 * Class Admin
 * @package App\Controllers
 */
class Admin extends Controller implements ControllerInterface
{
    /**
     * Describes controller methods
     * @return array
     */
    public function getMethods(): array { return
        [
            [
                "method"    => "admin",
                "router"    => ["*", "/admin/login/a/"],
                "priority"  => 0,
            ]
        ];
    }

    /**
     * return a http admin page
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function admin(string &$httpResponse, $args = []): bool {
        $templateLogger = new ViewLogger();
        $httpResponse = new \App\Views\Pages\Admin\Home($templateLogger, ["name" => "test"]);
        return true;
    }
}
