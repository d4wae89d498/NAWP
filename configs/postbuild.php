<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/29/2018
 * Time: 11:46 AM
 */

/**
 * Will simply copy .dist.env file to .env
 */
try {
    $curDir = __DIR__ . DIRECTORY_SEPARATOR;
    $source         = $curDir . ".env.dist";
    $destination    = $curDir . ".env";
    if (!file_exists($destination)) {
        if (!file_exists($source)) {
            throw new Exception($source . " file not found found!");
        }
        $sourceHandle     = fopen($source, "r+");
        $destinationHandle     = fopen($destination, "wr+");
        fwrite($destinationHandle, fread($sourceHandle, filesize($source)));
    }
} catch (Exception $ex) {
    echo "ERROR : " . PHP_EOL;
    echo $ex->getMessage();
}