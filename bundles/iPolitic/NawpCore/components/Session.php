<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/20/2018
 * Time: 5:24 PM
 */
namespace App\iPolitic\NawpCore\Components;
use App\iPolitic\NawpCore\Kernel;

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
    public const SESSION_FILE = 'sessions.txt';

    /**
     * Will return the full path to self::SESSION_FILE
     * @return string
     */
    public static function getFilePath(): string {
        return join(DIRECTORY_SEPARATOR, [Kernel::getKernel()->cachePath, self::SESSION_FILE]);
    }

    /**
     * Will generate a unic token per visitor. Will not generate a single cookie
     */
    public static function id(): string {
        return sha1(base64_encode(print_r([$_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $_SERVER["HTTP_COOKIE"]], 1)));
    }

    /**
     * Will return a session value using a vistor token
     * @param string $id
     * @param string $key
     * @return mixed
     */
    public static function get(string $key, string $id = "") {
        $id = $id !== "" ? $id : Session::id();
        return self::$session[$id][$key];
    }

    /**
     * Will set a session value using a key
     * @param string $id
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value, string $id = ""): void {
        $id = $id !== "" ? $id : Session::id();
        self::$session[$id][$key] = $value;
        return;
    }

    /**
     * Return true if the key exists for this visitorToken
     * @param string $id
     * @param string $key
     * @return bool
     */
    public static function isset(string $key, string $id = "") : bool {
        $id = $id !== "" ? $id : Session::id();
        return isset(self::$session[$id][$key]);
    }

    /**
     * Returns true if visitorToken (current user) is loggen in
     * @param string $id
     * @return bool
     */
    public static function isLoggedIn(string $id = "") : bool {
        $id = $id !== "" ? $id : Session::id();
        return isset(self::$session[$id]);
    }

    /**
     * Will destroy a session
     * @param string $id
     */
    public static function destroy(string $id = "") : void {
        $id = $id !== "" ? $id : Session::id();
        unset(self::$session[$id]);
    }

    /**
     * Will log in the given vistorToken, a generate its expire date
     * @param $id
     */
    public static function logIn(string $id = ""): void {
        $id = $id !== "" ? $id : Session::id();
        self::$session[$id] = [];
        self::$session[$id]["expire_date"] = strtotime(date("Y-m-d H:i:s", strtotime('+'.self::sessionSecondsDuration.' seconds')));
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
        $handle = fopen(self::getFilePath(), "wr+");
        fwrite($handle, serialize(self::$session));
        return;
    }

    /**
     * Will load the session var using a serialized txt file if one
     */
    public static function init() {
        if(file_exists(self::getFilePath()) && (filesize(self::getFilePath()) > 0)) {
            $handle = fopen(self::getFilePath(), "r");
            self::$session = unserialize(fread($handle, filesize(self::getFilePath())));
        } else {
            $handle = fopen(self::getFilePath(), "w+");
            self::$session = [];
            fwrite($handle, serialize(self::$session));
        }
    }

    /**
     * Will tick the session and clean the expired ones if needed
     */
    public static function tick(): void {

        /**
         * If a prime number is generated, we check for token expirity
         */
        $generatedNumber = 0;
        if ( call_user_func_array(
            function ($n)use(&$generatedNumber){for($i=~-$n**.5|0;$i&&$n%$i--;);return!$i&$n>2|$n==2; },
            [$generatedNumber = mt_rand()]
            )
        )   self::tokenExpireCheck();
        $token = self::id();
        if(!self::isLoggedIn($token)) {
            self::logIn($token);
        }
        return;
    }
}