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
use App\iPolitic\NawpCore\Components\Session;
use Workerman\Protocols\Http;
use Workerman\Protocols\HttpCache;

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
                "method"    => "adminMiddleware",
                "router"    => ["*", "*"],
                "priority"  => 98,
            ],
            [
                "method"    => "login",
                "router"    => ["*", "/admin/login"],
                "priority"  => 98,
            ]
        ];
    }

    /**
     * Bind the login page of the admin backend
     * @param string $httpResponse
     * @param array $args
     * @param string $requestType
     * @return bool
     */
    public function login(string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool {
        $templateLogger = new ViewLogger();
        $httpResponse = new \App\Views\Pages\Admin\Page
        (
            $templateLogger,
            [
                "header" => new \App\Views\Elements\Admin\Header($templateLogger, [
                    "page" => "Login",
                ]),
                "footer" => new \App\Views\Elements\Admin\Footer($templateLogger, []),
                "pageElement" => new \App\Views\Elements\Admin\Login($templateLogger, []),
            ]
        );
        return true;
    }

    /**
     * The admin middleware function
     * @param string $httpResponse
     * @param array $args
     * @param string $requestType
     * @return bool
     */
    public function adminMiddleware(string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool {
        echo "IN ADMINMIDDLEWARE OF REQUEST : ";
        var_dump($args);
        echo PHP_EOL;
        if(stristr($_SERVER["REQUEST_URI"], "/admin")) {
            // if user requested a page that is not blacklisted (ex: login, register pages), and if user is not authenticated
            if (!Session::isset( "user_id") && !stristr($_SERVER["REQUEST_URI"], "/login")) {
                // We redirect him to the login page
                Http::header('Location: /admin/login');
                // We release the request
                return true;
            }
        }
        // We continue request flow
        return false;
    }
}
