<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/13/2018
 * Time: 12:22 PM
 */
namespace App\iPolitic\NawpCore\components;

use Workerman\Protocols\Http;

/**
 * Class Cookie
 * @package App\iPolitic\NawpCore\components
 */
class Cookie
{
    public const DEFAULT_COOKIE_DURATION = 30; // in seconds
    public const DEFAULT_TEST_COOKIE_STR = "TEST_COOKIE"; // test cookie name for checking if cookies are enabled or not
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $value;
    /**
     * @var int
     */
    public $duration;

    /**
     * Cookie constructor.
     * @param string $name
     * @param string $value
     * @param int $duration
     */
    public function __construct(string $name, string $value, int $duration = self::DEFAULT_COOKIE_DURATION)
    {
        $this->name = $name;
        $this->value = $value;
        $this->duration = $duration;
    }

    /**
     * Will set a cookie
     * @param Cookie $cookie
     */
    public static function setHttpCookie(Cookie $cookie): void {
        $expireDate = date("D, d M Y H:i:s", time() + $cookie->duration) . 'GMT';
        Http::header("Set-Cookie: {$cookie->name}={$cookie->value}; EXPIRES{$expireDate};");
        return;
    }

    /**
     * Should set cookie in http header, 20
     * @param ViewLogger $viewLogger
     * @param Cookie $cookie
     */
    public static function set(ViewLogger &$viewLogger, Cookie $cookie): void {
        if ($viewLogger->requestType !== "SOCEKT") {
            self::setHttpCookie($cookie);
        }
        $GLOBALS["_COOKIE"][$cookie->name] = $_COOKIE[$cookie->name] = $cookie->value;
        $viewLogger->cookies[$cookie->name] = $cookie;
        return;
    }


    /**
     * Will remove a cookie from an hhtp request
     * @param string $cookieName
     */
    public static function removeHttpCookie(string $cookieName): void {
        $val = "";
        $expireDate = date("D, d M Y H:i:s", time() - 3600) . 'GMT';
        Http::header("Set-Cookie: {$cookieName}={$val}; EXPIRES{$expireDate};");
    }

    /**setTestCookie
     * Will set a first cookie so that we can test it later
     * @param ViewLogger $viewLogger
     */
    public static function setTestCookie(ViewLogger &$viewLogger): void {
        if (!self::areCookieEnabled($viewLogger)) {
            self::set
            (
                $viewLogger,
                new Cookie
                (
                    self::DEFAULT_TEST_COOKIE_STR,
                    self::DEFAULT_TEST_COOKIE_STR,
                    self::DEFAULT_COOKIE_DURATION
                )
            );
        }
        return;
    }

    /**
     * @param ViewLogger $viewLogger
     * @return bool
     */
    public static function areCookieEnabled(ViewLogger &$viewLogger): bool {
        return self::isset($viewLogger, self::DEFAULT_TEST_COOKIE_STR);
    }

    /**
     * @param ViewLogger $viewLogger
     * @param string $name
     * @return string
     */
    public static function get(ViewLogger &$viewLogger, string $name): string {
        return $viewLogger->cookies[$name];
    }

    /**
     * @param ViewLogger $viewLogger
     * @param string $key
     * @return bool
     */
    public static function  isset(ViewLogger $viewLogger, string $key): bool {
        return isset($viewLogger->cookies[$key]);
    }

    /**
     * @param ViewLogger $viewLogger
     * @param string $key
     */
    public static function  remove(ViewLogger &$viewLogger, string $key): void {
        if ($viewLogger->requestType !== "SOCKET") {
            self::removeHttpCookie($key);
        }
        unset($viewLogger->cookies[$key]);
        return;
    }

    /**
     * @param ViewLogger $viewLogger
     */
    public static function  destroy(ViewLogger &$viewLogger): void {
        $viewLogger->cookies = [];
        return;
    }
}