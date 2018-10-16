<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Controllers;

use App\DataSources\User\UserMapper;
use App\iPolitic\NawpCore\Components\PacketAdapter;
use App\iPolitic\NawpCore\Components\ViewLogger;
use App\iPolitic\NawpCore\Components\Controller;
use App\iPolitic\NawpCore\Interfaces\ControllerInterface;
use App\iPolitic\NawpCore\Components\Session;
use App\iPolitic\NawpCore\Kernel;
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
                "priority"  => 0,
            ],
            [
                "method"    => "adminHome",
                "router"    => ["*", "/admin"],
                "priority"  => 0,
            ]
        ];
    }

    /**
     *  Bind the login page of the admin backend
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @return bool
     * @throws \iPolitic\Solex\RouterException
     */
    public function login(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool {
        $loginMessage = "";
        $atlas = (Kernel::getKernel())->atlas;
        if(isset($_POST["email"]) && isset($_POST["password"])) {
            $userRecord = $atlas->select(UserMapper::class)
                ->where('email = ?', $_POST["email"])
                ->fetchRecord();
            if ($userRecord->hashed_password !== sha1($_POST["password"] . $_ENV["PASSWORD_SALT"])) {
                $loginMessage = "Mot de passe ou utilisateur incorect (".sha1($_POST["password"] . $_ENV["PASSWORD_SALT"]).")";
            } else {
                Session::set( "user_id", $userRecord->row_id);
                PacketAdapter::redirectTo($httpResponse, $viewLogger, "/admin", $args, $viewLogger->requestType);
                return true;
            }
        }



        $httpResponse .= " <!DOCTYPE html>
        <html lang=\"en\">" .
            new \App\Views\Elements\Admin\Header
                ($viewLogger, [
                    "page" => "Login",
                    "title" => "TEST".rand(0,99),
                    "url" => $_SERVER["REQUEST_URI"],
                    "cookies" => base64_encode(json_encode($viewLogger->cookies)),
                ]) .
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
                                "message" => $loginMessage,
                                "rand" => rand(0,9),
                                "cookie_on" => $viewLogger->areCookieEnabled ? "true" : "false",
                                "cookiestr" => print_r($_COOKIE,1)
                            ])),
                        ],
                    ]
                ) .
                new \App\Views\Elements\Admin\Footer($viewLogger, []) . "
            </body>
        </html>";
        return true;
    }

    /**
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function adminHome(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool {
        $loginMessage = "SUCCESS";
        $httpResponse .= "<!DOCTYPE html><html lang=\"en\">" .
            new \App\Views\Elements\Admin\Header(
                $viewLogger, ["page" => "Login", "title" => "TEST".rand(0,99), "url" => $_SERVER["REQUEST_URI"]]
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
                            "message" => $loginMessage,
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
     * The admin middleware function, manage common features of all /admin* matches
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @return bool
     * @throws \iPolitic\Solex\RouterException
     */
    public function adminMiddleware(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool {
        echo "IN ADMINMIDDLEWARE OF REQUEST : ";
        var_dump($args);
        echo PHP_EOL;
        if(stristr($_SERVER["REQUEST_URI"], "/admin")) {
            // if user requested a page that is not blacklisted (ex: login, register pages), and if user is not authenticated
            if (!Session::isset( "user_id") && !stristr($_SERVER["REQUEST_URI"], "/login")) {
                // We redirect him to the login page
                // TODO : add possibility to redirect from packet
                PacketAdapter::redirectTo($httpResponse, $viewLogger, "/admin/login", $args, $viewLogger->requestType);
                // We release the request
                return true;
            }
        }
        // We continue request flow
        return false;
    }
}
