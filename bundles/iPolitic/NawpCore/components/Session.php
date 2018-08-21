<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/20/2018
 * Time: 5:24 PM
 */
namespace App\iPolitic\NawpCore\Components;
/**
 * Class Session
 * Provide php native session replacement
 * @package App\iPolitic\NawpCore\Components
 */
class Session
{
    /**
     * The session array
     * @var array
     */
    public static $session = [];
    /**
     * Session duration before expiration
     */
    public const sessionSecondsDuration = 45 * 60; // 45 min
    /**
     * The session file name when is stored serialmized data
     */
    public const sessionFile = 'sessions.txt';

    /**
     * Will generate a unic token per visitor. Will not generate a single cookie
     */
    public static function id(): string {
        return sha1(base64_encode(print_r([$_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SERVER["HTTP_COOKIE"]], 1)));
    }

    /**
     * Will return a session value using a vistor token
     * @param string $visitorToken
     * @param string $key
     * @return mixed
     */
    public static function get(string $visitorToken, string $key) {
        return self::$session[$visitorToken][$key];
    }

    /**
     * Will set a session value using a key
     * @param string $visitorToken
     * @param string $key
     * @param string $value
     * @return string
     */
    public static function set(string $visitorToken, string $key, string$value): void {
        self::$session[$visitorToken][$key] = $value;
        return;
    }

    /**
     * Return true if the key exists for this visitorToken
     * @param string $visitorToken
     * @param string $key
     * @return bool
     */
    public static function isset(string $visitorToken, string $key) : bool {
        return isset(self::$session[$visitorToken][$key]);
    }

    /**
     * Returns true if visitorToken (current user) is loggen in
     * @param string $visitorToken
     * @return bool
     */
    public static function isLoggedIn(string $visitorToken) : bool {
        return isset(self::$session[$visitorToken]);
    }

    /**
     * Will destroy a session
     * @param string $visitorToken
     */
    public static function destroy(string $visitorToken) : void {
        unset(self::$session[$visitorToken]);
    }

    /**
     * Will log in the given vistorToken, a generate its expire date
     * @param $visitorToken
     */
    public static function logIn($visitorToken): void {
        self::$session[$visitorToken] = [];
        self::$session[$visitorToken]["expire_date"] = strtotime(date("Y-m-d H:i:s", strtotime('+'.self::sessionSecondsDuration.' seconds')));
        return;
    }

    /**
     * Will destroy all expired session
     */
    public static function tokenExpireCheck(): void {
        echo "checking expirity ..." . PHP_EOL;
        foreach (self::$session as $token => $v) {
            if(!isset(self::$session[$token]["expire_date"]) || (self::$session[$token]["expire_date"] <  strtotime(date("Y-m-d H:i:s")))) {
                echo "token expired" . PHP_EOL;
                self::destroy($token);
            }
        }
        // write serialized txt to self::sessionFile
        $handle = fopen(self::sessionFile, "wr+");
        fwrite($handle, serialize(self::$session));
        return;
    }

    /**
     * Will load the session var using a serialized txt file if one
     */
    public static function init() {
        if(empty(self::$session) && file_exists(self::sessionFile)) {
            $handle = fopen(self::sessionFile, "r+");
            self::$session = unserialize(fread($handle, filesize(self::sessionFile)));
        }
    }

    /**
     * Will tick the session and clean the expired ones if needed
     */
    public static function tick(): void {
        /**
         * If a prime number is generated, we check for token expirity
         */
        $a = 0; if(call_user_func_array(function ($n)use(&$a){for($i=~-$n**.5|0;$i&&$n%$i--;);return!$i&$n>2|$n==2; }, [$a = mt_rand()])) {
            echo "PRIME GENERATED : " . $a;
            self::tokenExpireCheck();
        }
        $token = self::id();
        if(!self::isLoggedIn($token)) {
            self::logIn($token);
        }
        return;
    }
}