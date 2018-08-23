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
use App\iPolitic\NawpCore\Components\Session as SupSession;
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

    public function login(string &$httpResponse, $args = []): bool {
        $templateLogger = new ViewLogger();
        $httpResponse = new \App\Views\Pages\Admin\Page
        (
            $templateLogger,
            [
                "elements" =>  [
                    new \App\Views\Elements\Admin\Header($templateLogger, [
                        "page" => "Login",
                    ]),
                    new \App\Views\Elements\Admin\Login($templateLogger, []),
                    new \App\Views\Elements\Admin\Footer($templateLogger, []),
                ]
            ]
        );
        return true;
    }

    /**
     * The admin middleware function
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function adminMiddleware(string &$httpResponse, $args = []): bool {
        if(stristr($_SERVER["REQUEST_URI"], "/admin")) {
            $user_token = SupSession::id();
            // if user requested a page that is not blacklisted (ex: login, register pages), and if user is not authenticated
            if (!SupSession::isset($user_token, "user_id") && !stristr($_SERVER["REQUEST_URI"], "/login")) {
                // We redirect him to the login page
                Http::header('Location: /admin/login');
                // We release the request
                return true;
            }
        }
        // We continue request flow
        return false;
    }

    /**
     * return a http admin page
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function admin(string &$httpResponse, $args = []): bool {
        $templateLogger = new ViewLogger();
        $httpResponse = new \App\Views\Pages\Admin\Page($templateLogger, ["name" => "test"]);
        // We release the request
        return true;
    }
}
