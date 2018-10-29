<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Controllers;

use App\DataSources\User\User;
use App\Ipolitic\Nawpcore\Components\Cookie;
use App\Ipolitic\Nawpcore\Components\PacketAdapter;
use App\Ipolitic\Nawpcore\Components\Utils;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use App\Ipolitic\Nawpcore\Components\Controller;
use App\Ipolitic\Nawpcore\Interfaces\ControllerInterface;

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
     * Bind the login page of the admin backend
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \iPolitic\Solex\RouterException
     * @throws \Exception
     */
    public function login(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {
        $loginMessage = "default";
        $atlas = $viewLogger->kernel->atlas;
        if (isset($_POST["email"]) && isset($_POST["password"])) {
            $userRecord = $atlas
                ->select(User::class)
                ->where('email = ', $_POST["email"])
                ->fetchRecord();

            if (($userRecord === null) || ($userRecord->hashed_password !== Utils::hashPassword($_POST["password"]))) {
                $this->logger->alert("LOGIN REFUSED");
                // wrong email and/or password
                $loginMessage = "Mot de passe ou utilisateur incorect (" . sha1($_POST["password"] . $_ENV["PASSWORD_SALT"]).")";
            } else {
                $this->logger->alert("LOGIN SUCCESS");
                $uid = Utils::generateUID(9);
                $url = "/admin";
                if ($viewLogger->cookiePoolInstance->areCookieEnabled()) {
                    $viewLogger->cookiePoolInstance->set(new Cookie("UID", $uid));
                } else {
                    $url = Utils::buildUrlParams($url, ["UID" => $uid]);
                }
                $_GET["UID"] = $uid;
                $viewLogger->sessionInstance->set("user_id", 5);
                //$loginMessage = $loginMessage . $url . " UID : " . Session::id($viewLogger);
                PacketAdapter::redirectTo($httpResponse, $viewLogger, $args, $viewLogger->requestType);
                return true;
            }
        }
        $loginMessage = $viewLogger->sessionInstance->id() . " || " . print_r($_POST, true);
        $httpResponse .= " <!DOCTYPE html>
        <html lang=\"en\">" .
            new \App\Views\Elements\Admin\Header($viewLogger, $this->logger, [
                    "page" => "Login",
                    "title" => "TEST".rand(0, 99),
                    "url" => $_SERVER["REQUEST_URI"],
                    "cookies" => base64_encode(json_encode($viewLogger->cookies)),
                ]) .
            "<body>" .
            new \App\Views\Pages\Admin\Page(

                    $viewLogger,
                    $this->logger,
                    [
                        "pass" => isset($_POST["password"]) ? $_POST["password"] : "emptypass!",
                        "html_elements" => [
                            (
                                new \App\Views\Elements\Admin\Login($viewLogger, $this->logger, [
                                "email" => isset($_POST["email"]) ? $_POST["email"] : null,
                                "message" => $loginMessage,
                                "rand" => rand(0, 9),
                                "cookie_on" => $viewLogger->areCookieEnabled ? "true" : "false",
                                "cookiestr" => print_r($viewLogger->cookies, true)
                            ])),
                        ],
                    ]
                ) .
                new \App\Views\Elements\Admin\Footer($viewLogger, $this->logger, []) . "
            </body>
        </html>";
        return true;
    }

    /**
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @return bool
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function adminHome(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {
        $loginMessage = "SUCCESS";
        $httpResponse .= "<!DOCTYPE html><html lang=\"en\">" .
            new \App\Views\Elements\Admin\Header(
                $viewLogger,
                $this->logger,
                ["page" => "Login", "title" => "TEST".rand(0, 99), "url" => $_SERVER["REQUEST_URI"]]
            ) .
            "<body>" .
            new \App\Views\Pages\Admin\Page(

                $viewLogger,
                $this->logger,
                [
                    "pass" => isset($_POST["password"]) ? $_POST["password"] : "emptypass!",
                    "html_elements" => [
                        (
                        new \App\Views\Elements\Admin\Login(
                            $viewLogger,
                            $this->logger,
                            [
                            "email" => isset($_POST["email"]) ? $_POST["email"] : null,
                            "message" => $loginMessage . " SESSION : " . print_r($viewLogger->sessionInstance->getAll(), true),
                            "rand" => rand(0, 9)
                        ]
                        )),
                    ],
                ]
            ) .
            new \App\Views\Elements\Admin\Footer($viewLogger, $this->logger, [])
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
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \iPolitic\Solex\RouterException
     * @throws \Exception
     */
    public function adminMiddleware(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {
        if (stristr($_SERVER["REQUEST_URI"], "/admin")) {
            // if user requested a page that is not blacklisted (ex: login, register pages), and if user is not authenticated
            if (!$viewLogger->sessionInstance->has("user_id") && !stristr($_SERVER["REQUEST_URI"], "/login")) {
                // We redirect him to the login page
                $_SERVER["REQUEST_URI"] = "/admin/login";
                PacketAdapter::redirectTo($httpResponse, $viewLogger, $args, $viewLogger->requestType);
                // We release the request
                return true;
            }
        }
        // We continue request flow
        return false;
    }
}
