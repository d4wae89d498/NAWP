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
        $atlas              = $viewLogger->kernel->atlas;
        $loginMessage       = "default";
        $loginFields        = ["firstName", "lastName", "birthPlace", "birthDay", "pin", "pin2", "accessTypeRadio"];
        $registrationFields = ["birthDay", "pin2"];
        $defaultLoginType   = "login";
        /**
         * convert a field name to an error message, or null if all checks succeed
         * @param $k
         * @return null|string
         */
        $fieldToError = function ($k) {
            $upCase = ucfirst($k);
            return (!empty($_POST[$k]) or !isset($_POST[$k])) ? null : "{$upCase} must not be empty.";
        };
        // initializing login fields
        $newTab = [];
        for ($i = 0; $i < count($loginFields); $i++) :
            $newTab[$loginFields[$i]] = ["v" => null, "m" => null];
        endfor;
        if (!isset($_POST["accessTypeRadio"])) :
            $newTab["accessTypeRadio"] = ["v" => $defaultLoginType, "m" => null];
        endif;
        $loginFields = $newTab;
        if (isset($_POST["accessTypeRadio"])):
            foreach ($loginFields as $k => $v):
                if (isset($_POST["accessTypeRadio"]) && !(($_POST["accessTypeRadio"] == "login") and in_array($k, $registrationFields))) :
                    $loginFields[$k]["v"] = $fieldToError($k) === null ? $_POST[$k] : "";
                    $loginFields[$k]["m"] = $fieldToError($k);
                endif;
            endforeach;
        endif;
        /**
         * return true if we can proceed the form
         * @return bool
         */
        $allFieldsAreCorrect = function () use ($loginFields, $fieldToError, $registrationFields) : bool {
            $return = true;
            foreach ($loginFields as $k => $v) {
                if (isset($_POST["accessTypeRadio"]) && (($_POST["accessTypeRadio"] == "login") xor in_array($k, $registrationFields))) {
                    $return = $return && ($fieldToError($k) == null);
                }
            }
            return $return;
        };
        // proceed the form
        if (isset($_POST["accessTypeRadio"])) :
            if ($allFieldsAreCorrect()) :
                switch ($_POST["accessTypeRadio"]):
                    case "register":
                                $loginMessage = "IN REGISTER WITH VALID POSTS";
                        break;
                    case "login":
                        $userRecord = $atlas
                        ->select(User::class)
                        ->where('email = ', $_POST["firstName"])
                        ->fetchRecord();
                        if (($userRecord === null) || ($userRecord->hashed_password !== Utils::hashPassword($_POST["pin"]))) :
                            // wrong email and/or password
                            $loginMessage = "<font color=\"red\">Mot de passe ou utilisateur incorect (" . sha1($_POST["pin"] . $_ENV["PASSWORD_SALT"]).")</font>"; else:
                            $_GET["UID"] = $uid = Utils::generateUID(9);
                            $url = "/admin";
                            if ($viewLogger->cookiePoolInstance->areCookieEnabled()):
                                $viewLogger->cookiePoolInstance->set(new Cookie("UID", $uid));
                            else:
                                $url = Utils::buildUrlParams($url, ["UID" => $uid]);
                            endif;
                            $viewLogger->sessionInstance->set("user_id", 5);
                            $viewLogger->redirectTo($httpResponse, $url, $args);
                            return true;
                        endif;
                    break;
                endswitch;
            else:
                $loginMessage = "<font color=\"red\">Please fill incorrect fields</font>";
            endif;
        endif;
        // rendering the page
        $newBody = $viewLogger->kernel->factories->getStreamFactory()->createStream();
        $newBody->write($viewLogger->render(
            ["\App\Server\Views\Elements\Admin\Header" => [
                "page" => "Login",
                "title" => "TEST".rand(0, 99),
                "url" => $_SERVER["REQUEST_URI"]]],
            ["\App\Server\Views\Pages\Admin\Page" =>  [
                "pass"          => isset($_POST["pin"]) ? $_POST["pin"] : "emptypass!",
                "html_elements" => [
                    "\App\Server\Views\Elements\Admin\Login" => [
                        "email"     => isset($_POST["email"]) ? $_POST["email"] : null,
                        "message"   => $loginMessage,
                        "rand"      => rand(0, 9),
                        "fields"    => $loginFields,
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
