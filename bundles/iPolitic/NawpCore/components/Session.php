<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/20/2018
 * Time: 5:24 PM
 */

namespace iPolitic\NawpCore\components;


class Session
{
    public static $session = [];
    public const sessionSecondsDuration = 60 * 45; // 45 min

    /**
     * Will generate a unic token per visitor. Will not generate a single cookie
     */
    public static function getVisitorToken() {
        return sha1(base64_encode(print_r([$_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SERVER["HTTP_COOKIE"]], 1)));
    }

    /**
     * Will return a session value using a vistor token
     * @param string $visitorToken
     * @param string $key
     * @return mixed
     */
    public static function sessionGet(string $visitorToken, string $key) {
        return self::$session[$visitorToken][$key];
    }

    /**
     * Will set a session value using a key
     * @param string $visitorToken
     * @param string $key
     * @param string $value
     * @return string
     */
    public static function sessionSet(string $visitorToken, string $key, string$value) {
        return self::$session[$visitorToken][$key] = $value;
    }

    /**
     * Return true if the key exists for this visitorToken
     * @param string $visitorToken
     * @param string $key
     * @return bool
     */
    public static function sessionIsset(string $visitorToken, string $key) : bool {
        return isset(self::$session[$visitorToken][$key]);
    }

    /**
     * Returns true if visitorToken (current user) is loggen in
     * @param string $visitorToken
     * @return bool
     */
    public static function sessionIsloggedIn(string $visitorToken) : bool {
        return isset(self::$session[$visitorToken]);
    }

    /**
     * Will destroy a session
     * @param string $visitorToken
     */
    public static function sessionDestroy(string $visitorToken) : void {
        unset(self::$session[$visitorToken]);
    }

    /**
     * Will log in the given vistorToken, a generate its expire date
     * @param $visitorToken
     */
    public static function sessionLogIn($visitorToken): void {
        self::$session[$visitorToken] = [];
        self::$session[$visitorToken]["expire_date"] = strtotime(date("Y-m-d H:i:s", strtotime('+'.self::sessionSecondsDuration.' seconds')));
        return;
    }

    /**
     * Will destroy all expired session
     */
    public static function tokenExpireCheck() {
        // todo : save serialized session in a .txt file, and load it at server start
        echo "checking expirity ..." . PHP_EOL;
        foreach (self::$session as $token => $v) {
            if(!isset(self::$session[$token]["expire_date"]) || (self::$session[$token]["expire_date"] <  strtotime(date("Y-m-d H:i:s")))) {
                echo "token expired" . PHP_EOL;
                self::sessionDestroy($token);
            }
        }
    }

}