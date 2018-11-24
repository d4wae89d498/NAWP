<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/28/2018
 * Time: 7:53 PM
 */

namespace App\Ipolitic\Nawpcore\Components;

use App\Ipolitic\Nawpcore\Exceptions\NotFoundExceptionInterface;
use Psr\Container\ContainerInterface;
use Workerman\Protocols\Http;

/**
 * Class CookiePool
 * @package App\Ipolitic\Nawpcore\Components
 */
class CookiePool implements ContainerInterface
{
    /**
     * @var ViewLogger
     */
    public $viewLogger;

    /**
     * CookiePool constructor.
     * @param ViewLogger $viewLogger
     */
    public function __construct(ViewLogger $viewLogger)
    {
        $this->viewLogger = $viewLogger;
        $this->getAll();
    }

    /**
     * Will set a cookie
     * @param Cookie $cookie
     */
    public function setHttpCookie(Cookie $cookie): void
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
    public function removeHttpCookie(string $cookieName): void
    {
        $val = "";
        $expireDate = date("D, d-m-Y H:i:s", time() - 3600) . ' GMT';
        Http::header("Set-Cookie: {$cookieName}={$val}; Expires={$expireDate};");
    }

    /**
     * Will return all defined Http cookies
     * @return array
     */
    public function getHttpCookies(): array
    {
        return $_COOKIE;
    }

    /**
     * Will return true if this cookie name is allowed, false else.
     * @param string $cookieName
     * @return bool
     */
    public function isAllowedCookie(string $cookieName): bool
    {
        return in_array(
            $cookieName,
            array_merge(
                [Cookie::DEFAULT_TEST_COOKIE_STR],
                explode(",", getenv("COOKIE_WHITELIST"))
            )
        );
    }

    /**
     * Should set cookie in http header, 20
     * @param Cookie $cookie
     * @param bool $noHttp
     */
    public function set(Cookie $cookie, $noHttp = false): void
    {
        if (self::isAllowedCookie($cookie->name)) {
            if ($this->viewLogger->requestType !== "SOCKET") {
                if (!$noHttp) {
                    self::setHttpCookie($cookie);
                }
            }
            $this->viewLogger->cookies[$cookie->name] =
            $GLOBALS["_COOKIE"][$cookie->name] =
            $_COOKIE[$cookie->name] =
                $cookie->value;
        }
        return;
    }

    /**
     * Will set a first cookie so that we can test it later
     */
    public function setTestCookie(): void
    {
        if (!$this->areCookieEnabled()) {
            self::set(
                new Cookie(
                    Cookie::DEFAULT_TEST_COOKIE_STR,
                    Cookie::DEFAULT_TEST_COOKIE_STR,
                    Cookie::DEFAULT_COOKIE_DURATION
                )
            );
        }
        return;
    }

    /**
     * @return bool
     */
    public function areCookieEnabled(): bool
    {
        if ($this->has("disableCookie") && $this->has("disableCookie" == true)) {
            return false;
        } else {
            return $this->viewLogger->cookieEnabledLocked ? $this->viewLogger->areCookieEnabled : $this->has(Cookie::DEFAULT_TEST_COOKIE_STR);
        }
    }

    /**
     * @param string $key
     * @return string
     * @throws NotFoundExceptionInterface
     */
    public function get($key): string
    {
        if (!$this->has($key)) {
            throw new NotFoundExceptionInterface();
        }
        return
            $this->viewLogger->cookies[$key] =
            $_COOKIE[$key] =
                (
                isset($viewLogger->cookies[$key])
                    ?
                    $this->viewLogger->cookies[$key]
                    :
                    $_COOKIE[$key]
                );
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key): bool
    {
        return
            isset($viewLogger->cookies[$key])
                ?
                true
                :
                (isset($_COOKIE[$key]));
    }


    /**
     * @return array
     */
    public function getAll(): array
    {
        if (!is_array($_COOKIE)) {
            $_COOKIE = [];
        } else {
            foreach ($_COOKIE as $k => $v) {
                if (!$this->has($k)) {
                    $this->set(new Cookie($k, $v));
                }
            }
        }
        return $this->viewLogger->cookies;
    }

    /**
     * @param string $key
     */
    public function remove(string $key): void
    {
        if ($this->viewLogger->requestType !== "SOCKET") {
            self::removeHttpCookie($key);
        }
        unset($this->viewLogger->cookies[$key]);
        return;
    }

    /**
     * Will destroy all cookies
     */
    public function destroy(): void
    {
        foreach ($this->getAll() as $k => $v) {
            if (strlen(strval($k)) > 0) {
                $this->remove($k);
            }
        }
        return;
    }
}
