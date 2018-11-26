<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Server\Controllers;

use App\Ipolitic\Nawpcore\Collections\FieldCollection;
use App\Ipolitic\Nawpcore\Components\Query;
use App\Server\Models\User\User;
use App\Ipolitic\Nawpcore\Components\Cookie;
use App\Ipolitic\Nawpcore\Components\Utils;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use App\Ipolitic\Nawpcore\Components\Controller;
use App\Ipolitic\Nawpcore\Interfaces\ControllerInterface;
use App\Server\Views\Elements\Admin\Footer;
use App\Server\Views\Elements\Admin\Header;
use App\Server\Views\Elements\Admin\Login;
use App\Server\Views\Pages\Admin\Page;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;

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
        $registrationMode = false;
        if (isset($_POST["accessTypeRadio"])) {
            if ($_POST["accessTypeRadio"] === "login") {
                $registrationMode = false;
            } else {
                $registrationMode = true;
            }
        }
        $dateTime = new \DateTime();
        $dateTime->setTimestamp(0);
        $record                 = $viewLogger->kernel->atlas->newRecord(User::class, [
            "updated_at"        => $dateTime->format('Y-m-d H:i:s'),
            "inserted_at"       => $dateTime->format('Y-m-d H:i:s'),
            "email"             => "test@icloud.com",
            "birth_day"         => $dateTime->format('Y-m-d H:i:s'),
            "birth_place"       => "London, United Kingdom",
            "first_name"        => "john",
            "last_name"         => "doe",
            "hashed_password"   => "5684",
            "rgpd"              => true,
            "newsletter"        => true,
            "role_id"              => 1
        ]);
        $fieldCollection    = new FieldCollection($viewLogger->kernel, $record);
        $request            = new ServerRequest();
        $viewLogger         = new ViewLogger($viewLogger->kernel, $viewLogger->request);
        $fieldCollection    ->setViewLogger($viewLogger);
        $fieldCollection    ->fill();
        $fieldCollection    ->checkValidity();
        //var_dump($fieldCollection->getArrayCopy());
        // rendering the page
        $newBody = $viewLogger->kernel->factories->getStreamFactory()->createStream();
        $views = $fieldCollection->getViews();
        $newBody->write($viewLogger->render(
            [Header::class => [
                "page" => "Login",
                "title" => "TEST".rand(0, 99),
                "url" => $_SERVER["REQUEST_URI"]]],
            [Page::class =>  [
                "pass"          => isset($_POST["pin"]) ? $_POST["pin"] : "emptypass!",
                "html_elements" => [
                    [Login::class => [
                        "email"     => isset($_POST["email"]) ? $_POST["email"] : null,
                        "message"   => "test msg",
                        "rand"      => rand(0, 9),
                        "registration" => $registrationMode,
                        "html_elements" => $views,
                    ]],
                ],
            ]],
            [Footer::class => []]
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
                        (new \App\Server\Views\Elements\Admin\Login(
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
