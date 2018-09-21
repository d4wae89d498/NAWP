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
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @param string $requestType
     * @return bool
     */
    public function login(ViewLogger &$viewLogger, string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool {
        $httpResponse .= "<!DOCTYPE html><html lang=\"en\">" .
    new \App\Views\Elements\Admin\Header(
        $viewLogger, ["page" => "Login",]
    ) .
    "<body class=\"fix-header fix-sidebar card-no-border\">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class=\"preloader\">
            <svg class=\"circular\" viewBox=\"25 25 50 50\">
            <circle class=\"path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke-width=\"2\" stroke-miterlimit=\"10\" /> </svg>
        </div>" .
        new \App\Views\Pages\Admin\Page
        (

            $viewLogger,
            [
                "pass" => isset($_POST["password"]) ? $_POST["password"] : "emptypass!",
                "html_elements" => [
                    (
                        new \App\Views\Elements\Admin\Login($viewLogger, [
                        "email" => isset($_POST["email"]) ? $_POST["email"] : null,
                        "rand" => rand(0,9)
                    ])),
                ],
            ]
        ) .
        new \App\Views\Elements\Admin\Footer($viewLogger, [])
        .
    "</body></html>";
        return true;
    }

    /**
     * The admin middleware function
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @param string $requestType
     * @return bool
     */
    public function adminMiddleware(ViewLogger &$viewLogger, string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool {
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
