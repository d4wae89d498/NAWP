<?php declare(strict_types=1);
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
    public const COOKIE8_SID_KEY = "SID";
    public const DEFAULT_COOKIE_DURATION = 30 * 60; // in seconds
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
    public static function setHttpCookie(Cookie $cookie): void
    {
        if (self::isAllowedCookie($cookie->name)) {
            $expireDate = date("D, d-m-Y H:i:s", time() + $cookie->duration) . ' GMT';
            Http::header("Set-Cookie: {$cookie->name}={$cookie->value}; Expires={$expireDate};");
        }
        return;
    }

    /**
     * Will remove a cookie from an hhtp request
     * @param string $cookieName
     */
    public static function removeHttpCookie(string $cookieName): void
    {
        $val = "";
        $expireDate = date("D, d-m-Y H:i:s", time() - 3600) . ' GMT';
        Http::header("Set-Cookie: {$cookieName}={$val}; Expires={$expireDate};");
    }

    /**
     * Will return all defined Http cookies
     * @return array
     */
    public static function getHttpCookies(): array
    {
        return $_COOKIE;
    }

    /**
     * Will return true if this cookie name is allowed, false else.
     * @param string $cookieName
     * @return bool
     */
    public static function isAllowedCookie(string $cookieName): bool
    {
        $a = in_array(
            $cookieName,
            array_merge(
                [Cookie::DEFAULT_TEST_COOKIE_STR],
                explode(",", $_ENV["COOKIE_WHITELIST"])
            )
        );
        return $a;
    }

    /**
     * Should set cookie in http header, 20
     * @param ViewLogger $viewLogger
     * @param Cookie $cookie
     * @param bool $noHttp
     */
    public static function set(ViewLogger &$viewLogger, Cookie $cookie, $noHttp = false): void
    {
        if (self::isAllowedCookie($cookie->name)) {
            if ($viewLogger->requestType !== "SOCEKT") {
                if (!$noHttp) {
                    self::setHttpCookie($cookie);
                }
            }
            $viewLogger->cookies[$cookie->name] =
            $GLOBALS["_COOKIE"][$cookie->name] =
            $_COOKIE[$cookie->name] =
                $cookie->value;
        }
        return;
    }

    /**setTestCookie
     * Will set a first cookie so that we can test it later
     * @param ViewLogger $viewLogger
     */
    public static function setTestCookie(ViewLogger &$viewLogger): void
    {
        if (!self::areCookieEnabled($viewLogger)) {
            self::set(
                $viewLogger,
                new Cookie(
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
    public static function areCookieEnabled(ViewLogger &$viewLogger): bool
    {
        return $viewLogger->cookieEnabledLocked ? $viewLogger->areCookieEnabled : self::isset($viewLogger, self::DEFAULT_TEST_COOKIE_STR);
    }

    /**
     * @param ViewLogger $viewLogger
     * @param string $name
     * @return string
     */
    public static function get(ViewLogger &$viewLogger, string $name): string
    {
        return
            $viewLogger->cookies[$name] =
            $_COOKIE[$name] =
            (
                isset($viewLogger->cookies[$name])
            ?
                $viewLogger->cookies[$name]
            :
                $_COOKIE[$name]
            );
    }

    /**
     * @param ViewLogger $viewLogger
     * @param string $key
     * @return bool
     */
    public static function isset(ViewLogger $viewLogger, string $key): bool
    {
        return
            isset($viewLogger->cookies[$key])
            ?
                true
            :
                (isset($_COOKIE[$key]));
    }

    /**
     * @param ViewLogger $viewLogger
     * @param string $key
     */
    public static function remove(ViewLogger &$viewLogger, string $key): void
    {
        if ($viewLogger->requestType !== "SOCKET") {
            self::removeHttpCookie($key);
        }
        unset($viewLogger->cookies[$key]);
        return;
    }

    /**
     * @param ViewLogger $viewLogger
     */
    public static function destroy(ViewLogger &$viewLogger): void
    {
        $viewLogger->cookies = [];
        return;
    }
}
