<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Controllers;

use App\DataSources\User\User;
use App\iPolitic\NawpCore\components\Cookie;
use App\iPolitic\NawpCore\Components\PacketAdapter;
use App\iPolitic\NawpCore\Components\Utils;
use App\iPolitic\NawpCore\Components\ViewLogger;
use App\iPolitic\NawpCore\Components\Controller;
use App\iPolitic\NawpCore\Interfaces\ControllerInterface;
use App\iPolitic\NawpCore\Components\Session;
use App\iPolitic\NawpCore\Kernel;

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
    public function getMethods(): array
    {
        return
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
     * @throws \Exception
     */
    public function login(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {
        $loginMessage = "default";
        $atlas = (Kernel::getKernel())->atlas;
        if (isset($_POST["email"]) && isset($_POST["password"])) {
            $userRecord = $atlas
                ->select(User::class)
                ->where('email = ', $_POST["email"])
                ->fetchRecord();
            if ($userRecord->hashed_password !== Utils::hashPassword($_POST["password"])) {
                // wrong email and/or password
                $loginMessage = "Mot de passe ou utilisateur incorect (" . sha1($_POST["password"] . $_ENV["PASSWORD_SALT"]).")";
            } else {
                // success email / password combinaison
                $uid = Utils::generateUID(9);
                $url = "/admin";
                if (Cookie::areCookieEnabled($viewLogger)) {
                    Cookie::set($viewLogger, new Cookie("UID", $uid));
                } else {
                    $url = Utils::buildUrlParams($url, ["UID" => $uid]);
                }
                $_GET["UID"] = $uid;
                Session::set($viewLogger, "user_id", 5);
                // $loginMessage = $loginMessage . $url . " UID : " . Session::id($viewLogger);
                PacketAdapter::redirectTo($httpResponse, $viewLogger, $url, $args, $viewLogger->requestType);
                return true;
            }
        }



        $httpResponse .= " <!DOCTYPE html>
        <html lang=\"en\">" .
            new \App\Views\Elements\Admin\Header($viewLogger, [
                    "page" => "Login",
                    "title" => "TEST".rand(0, 99),
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
                new \App\Views\Pages\Admin\Page(

                    $viewLogger,
                    [
                        "pass" => isset($_POST["password"]) ? $_POST["password"] : "emptypass!",
                        "html_elements" => [
                            (
                                new \App\Views\Elements\Admin\Login($viewLogger, [
                                "email" => isset($_POST["email"]) ? $_POST["email"] : null,
                                "message" => $loginMessage,
                                "rand" => rand(0, 9),
                                "cookie_on" => $viewLogger->areCookieEnabled ? "true" : "false",
                                "cookiestr" => print_r($viewLogger->cookies, 1)
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
    public function adminHome(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {
        $loginMessage = "SUCCESS";
        $httpResponse .= "<!DOCTYPE html><html lang=\"en\">" .
            new \App\Views\Elements\Admin\Header(
                $viewLogger,
                ["page" => "Login", "title" => "TEST".rand(0, 99), "url" => $_SERVER["REQUEST_URI"]]
            ) .
            "<body class=\"fix-header fix-sidebar card-no-border\">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class=\"preloader\">
            <svg class=\"circular\" viewBox=\"25 25 50 50\">
            <circle class=\"path\" cx=\"50\" cy=\"50\" r=\"20\" fill=\"none\" stroke-width=\"2\" stroke-miterlimit=\"10\" /> </svg>
        </div>" .
            new \App\Views\Pages\Admin\Page(

                $viewLogger,
                [
                    "pass" => isset($_POST["password"]) ? $_POST["password"] : "emptypass!",
                    "html_elements" => [
                        (
                        new \App\Views\Elements\Admin\Login($viewLogger, [
                            "email" => isset($_POST["email"]) ? $_POST["email"] : null,
                            "message" => $loginMessage . " SESSION : " . print_r(Session::getAll($viewLogger), 1),
                            "cookie_on" => $viewLogger->areCookieEnabled ? "true" : "false",
                            "rand" => rand(0, 9)
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
     *  The admin middleware function, manage common features of all /admin* matches
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @return bool
     * @throws \iPolitic\Solex\RouterException
     * @throws \Exception
     */
    public function adminMiddleware(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {
        echo "IN ADMINMIDDLEWARE OF REQUEST : ";
        var_dump($args);
        var_dump($_GET);
        echo PHP_EOL;
        // exit;
        if (stristr($_SERVER["REQUEST_URI"], "/admin")) {
            // if user requested a page that is not blacklisted (ex: login, register pages), and if user is not authenticated
            if (!Session::isset($viewLogger, "user_id") && !stristr($_SERVER["REQUEST_URI"], "/login")) {
                // We redirect him to the login page
                PacketAdapter::redirectTo($httpResponse, $viewLogger, "/admin/login", $args, $viewLogger->requestType);
                // We release the request
                return true;
            }
        }
        // We continue request flow
        return false;
    }
}
