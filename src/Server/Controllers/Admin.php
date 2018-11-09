<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Server\Controllers;

use App\Ipolitic\Nawpcore\Components\Query;
use App\Server\Models\User\User;
use App\Ipolitic\Nawpcore\Components\Cookie;
use App\Ipolitic\Nawpcore\Components\Utils;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use App\Ipolitic\Nawpcore\Components\Controller;
use App\Ipolitic\Nawpcore\Interfaces\ControllerInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param ResponseInterface $response
     * @param array $args
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \iPolitic\Solex\RouterException
     * @throws \Exception
     */
    public function login(ViewLogger &$viewLogger, ResponseInterface &$response, array $args = []): bool
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
                $_GET["UID"] = $uid = Utils::generateUID(9);
                $url = "/admin";
                if ($viewLogger->cookiePoolInstance->areCookieEnabled()) {
                    $viewLogger->cookiePoolInstance->set(new Cookie("UID", $uid));
                } else {
                    $url = Utils::buildUrlParams($url, ["UID" => $uid]);
                }
                $viewLogger->sessionInstance->set("user_id", 5);
                $viewLogger->redirectTo($httpResponse, $url, $args);
                return true;
            }
        }
        $newBody = $viewLogger->kernel->factories->getStreamFactory()->createStream();
        $newBody->write($viewLogger->render(
            ["\App\Server\Views\Elements\Admin\Header" => [
                "page" => "Login", "title" => "TEST".rand(0, 99), "url" => $_SERVER["REQUEST_URI"]]],
            ["\App\Server\Views\Pages\Admin\Page" =>  [
                "pass" => isset($_POST["password"]) ? $_POST["password"] : "emptypass!",
                "html_elements" => [
                    "\App\Server\Views\Elements\Admin\Login" => [
                        "email" => isset($_POST["email"]) ? $_POST["email"] : null,
                        "message" => $loginMessage . " SESSION : " . print_r($_POST, true),
                        "rand" => rand(0, 9)
                    ],
                ],
            ]],
            ["\App\Server\Views\Elements\Admin\Footer" => []]
        ));
        $response = $response->withBody($newBody);
        return true;
    }

    /**
     * @param ViewLogger $viewLogger
     * @param ResponseInterface $response
     * @param array $args
     * @return bool
     * @throws \Exception
     */
    public function adminHome(ViewLogger &$viewLogger, ResponseInterface &$response, array $args = []): bool
    {
        $loginMessage = "SUCCESS";
        $response->getBody()->write("<!DOCTYPE html><html lang=\"en\">" .
            new \App\Server\Views\Elements\Admin\Header(
                $viewLogger,
                $this->logger,
                ["page" => "Login", "title" => "TEST".rand(0, 99), "url" => $_SERVER["REQUEST_URI"]]
            ) .
            "<body>" .
            new \App\Server\Views\Pages\Admin\Page(

                $viewLogger,
                $this->logger,
                [
                    "pass" => isset($_POST["password"]) ? $_POST["password"] : "emptypass!",
                    "html_elements" => [
                        (
                        new \App\Server\Views\Elements\Admin\Login(
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
            new \App\Server\Views\Elements\Admin\Footer($viewLogger, $this->logger, [])
            .
            "</body></html>");
        return true;
    }

    /**
     * The admin middleware function, manage common features of all /admin* matches
     * @param ViewLogger $viewLogger
     * @param ResponseInterface $response
     * @param array $args
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \iPolitic\Solex\RouterException
     * @throws \Exception
     */
    public function adminMiddleware(ViewLogger &$viewLogger, ResponseInterface &$response, array $args = []): bool
    {
        if (stristr($viewLogger->request->getServerParams()["REQUEST_URI"], "/admin")) {
            // if user requested a page that is not blacklisted (ex: login, register pages), and if user is not authenticated
            if (!$viewLogger->sessionInstance->has("user_id") && !stristr($viewLogger->request->getServerParams()["REQUEST_URI"], "/login")) {
                // We redirect him to the login page
                $viewLogger->redirectTo($response, "/admin/login", $args);
                // We release the request
                return true;
            }
        }
        // We continue request flow
        return false;
    }
}
