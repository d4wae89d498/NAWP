<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/13/2018
 * Time: 12:23 PM
 */

namespace App\iPolitic\NawpCore\interfaces;

use App\iPolitic\NawpCore\Components\ViewLogger;

/**
 * Interface CArray
 * @package App\iPolitic\NawpCore\interfaces
 */
interface CArray
{
    /**
     * @param string $key
     * @param string|ViewLogger $identifier
     * @return string
     */
    public static function  get(string $key, $identifier): string;

    /**
     * @param string $key
     * @param string $value
     * @param string|ViewLogger $identifier
     */
    public static function  set(string $key, string $value, $identifier): void;

    /**
     * @param string $key
     * @param string|ViewLogger $identifier
     * @return bool
     */
    public static function  isset(string $key, $identifier): bool;

    /**
     * @param string $key
     * @param string|ViewLogger $identifier
     */
    public static function  remove(string $key, $identifier): void;

    /**
     * @param string|ViewLogger $identifier
     */
    public static function  destroy($identifier): void;
}