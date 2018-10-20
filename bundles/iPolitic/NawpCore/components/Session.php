<?php declare(strict_type=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/20/2018
 * Time: 5:24 PM
 */
namespace App\iPolitic\NawpCore\Components;

use App\iPolitic\NawpCore\interfaces\CArray;
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
    public static function getFilePath(): string
    {
        return join(DIRECTORY_SEPARATOR, [Kernel::getKernel()->cachePath, self::SESSION_FILE]);
    }

    /**
     * @param ViewLogger $viewLogger
     * @return string
     * @throws \Exception
     */
    public static function id(ViewLogger $viewLogger): string
    {
        return
            (isset($_GET["UID"])
                ?
                $_GET["UID"]
                :
                (
                    Cookie::isset($viewLogger, "UID") ?
                        Cookie::get($viewLogger, "UID")
                    :
                        Utils::generateUID()
                )
            );
    }

    /**
     * Will return a session value using a visitor token
     * @param ViewLogger $viewLogger
     * @param string $key
     * @param string $id
     * @return string
     * @throws \Exception
     */
    public static function get(ViewLogger $viewLogger, string $key, $id = ""): string
    {
        $id = $id !== "" ? $id : Session::id($viewLogger);
        return self::$session[$id][$key];
    }

    /**
     * Will return a session value using a visitor token
     * @param ViewLogger $viewLogger
     * @param string $id
     * @return array
     * @throws \Exception
     */
    public static function getAll(ViewLogger $viewLogger, $id = ""): array
    {
        $id = $id !== "" ? $id : Session::id($viewLogger);
        return isset(self::$session[$id]) ? self::$session[$id] : [];
    }

    /**
     * Will set a session value using a key
     * @param ViewLogger $viewLogger
     * @param string $key
     * @param string $value
     * @param string $id
     * @throws \Exception
     */
    public static function set(ViewLogger $viewLogger, string $key, string $value, $id = ""): void
    {
        $id = $id !== "" ? $id : Session::id($viewLogger);
        self::$session[$id][$key] = $value;
        self::saveChanges();
        return;
    }

    /**
     * Return true if the key exists for this visitorToken
     * @param ViewLogger $viewLogger
     * @param string $key
     * @param string $id
     * @return bool
     * @throws \Exception
     */
    public static function isset(ViewLogger $viewLogger, string $key, $id = "") : bool
    {
        $id = $id !== "" ? $id : Session::id($viewLogger);
        return isset(self::$session[$id][$key]);
    }

    /**
     * Will remove an item if exists using its key
     * @param ViewLogger $viewLogger
     * @param string $key
     * @param string $id
     * @throws \Exception
     */
    public static function remove(ViewLogger $viewLogger, string $key, $id = "") : void
    {
        $id = $id !== "" ? $id : Session::id($viewLogger);
        if (self::isset($viewLogger, $key)) {
            unset(self::$session[$id][$key]);
        }
        self::saveChanges();
    }

    /**
     * Will destroy a session
     * @param ViewLogger $viewLogger
     * @param string $id
     * @throws \Exception
     */
    public static function destroy(ViewLogger $viewLogger, $id = "") : void
    {
        $id = $id !== "" ? $id : Session::id($viewLogger);
        unset(self::$session[$id]);
        self::saveChanges();
    }

    /**
     * Returns true if visitorToken (current user) is loggen in
     * @param ViewLogger $viewLogger
     * @param string $id
     * @return bool
     * @throws \Exception
     */
    public static function isLoggedIn(ViewLogger $viewLogger, string $id = "") : bool
    {
        $id = $id !== "" ? $id : Session::id($viewLogger);
        return isset(self::$session[$id]);
    }

    /**
     * Returns true if visitorToken (current user) is loggen in
     * @param ViewLogger $viewLogger
     * @param string $id
     * @throws \Exception
     */
    public static function logIn(ViewLogger $viewLogger, string $id = ""): void
    {
        $id = $id !== "" ? $id : Session::id($viewLogger);
        self::$session[$id] = [];
        self::$session[$id]["expire_date"] = strtotime(date("Y-m-d H:i:s", strtotime('+'.self::sessionSecondsDuration.' seconds')));
        self::saveChanges();
        return;
    }

    /**
     *  Will destroy all expired session
     * @param ViewLogger $viewLogger
     * @throws \Exception
     */
    public static function tokenExpireCheck(ViewLogger $viewLogger): void
    {
        echo "checking expirity ..." . PHP_EOL;
        foreach (self::$session as $token => $v) {
            if (!isset(self::$session[$token]["expire_date"]) || (self::$session[$token]["expire_date"] <  strtotime(date("Y-m-d H:i:s")))) {
                echo "token expired" . PHP_EOL;
                self::destroy($viewLogger, $token);
            }
        }
        self::saveChanges();
        return;
    }

    /**
     * Will load the session var using a serialized txt file if one
     */
    public static function init()
    {
        if (file_exists(self::getFilePath()) && (filesize(self::getFilePath()) > 0)) {
            $handle = fopen(self::getFilePath(), "r");
            self::$session = unserialize(fread($handle, filesize(self::getFilePath())));
        } else {
            self::$session = [];
            self::saveChanges();
        }
    }

    /**
     * Will tick the session and clean the expired ones if needed
     * @param viewLogger $viewLogger
     * @throws \Exception
     */
    public static function tick(viewLogger $viewLogger): void
    {

        /**
         * If a prime number is generated, we check for token expirity
         */
        $generatedNumber = 0;
        if (call_user_func_array(
            function ($n) use (&$generatedNumber) {
                for ($i=~-$n**.5|0;$i&&$n%$i--;) {
                    sleep(0);
                }
                return!$i&$n>2|$n==2;
            },
            [$generatedNumber = mt_rand()]
            )
        ) {
            self::tokenExpireCheck($viewLogger);
        }
        $token = self::id($viewLogger);
        if (!self::isLoggedIn($viewLogger, $token)) {
            self::logIn($viewLogger, $token);
        }
        self::saveChanges();
        return;
    }

    /**
     * Will save the current sessions
     */
    public static function saveChanges()
    {
        $handle = fopen(self::getFilePath(), "w");
        fwrite($handle, serialize(self::$session));
    }
}
