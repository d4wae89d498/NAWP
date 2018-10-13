<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/13/2018
 * Time: 12:22 PM
 */
namespace App\iPolitic\NawpCore\components;

use App\iPolitic\NawpCore\interfaces\CArray;

/**
 * Class Cookie
 * @package App\iPolitic\NawpCore\components
 */
class Cookie implements CArray
{
    /**
     * @param string $key
     * @param ViewLogger $viewLogger
     * @return string
     */
    public static function  get(string $key, $viewLogger): string {

    }

    /**
     * @param string $key
     * @param string $value
     * @param ViewLogger $viewLogger
     */
    public static function  set(string $key, string $value, $viewLogger): void {
        return;
    }

    /**
     * @param string $key
     * @param ViewLogger $viewLogger
     * @return bool
     */
    public static function  isset(string $key, $viewLogger): bool {

    }

    /**
     * @param string $key
     * @param ViewLogger $viewLogger
     */
    public static function  remove(string $key, $viewLogger): void {
        return;
    }

    /**
     * @param ViewLogger $viewLogger
     */
    public static function  destroy($viewLogger): void {
        return;
    }
}