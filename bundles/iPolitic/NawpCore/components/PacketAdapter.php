<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 9/1/2018
 * Time: 12:06 PM
 */

namespace App\iPolitic\NawpCore\Components;

use App\iPolitic\NawpCore\Kernel;

class PacketAdapter
{
    public const PACKET_ADAPTER_FOLDER = "packet_adapter";

    public static function init(): void {
        $files = glob(
            join(
                DIRECTORY_SEPARATOR, [
                Kernel::getKernel()->cachePath, self::PACKET_ADAPTER_FOLDER , "*"]
            ));
        foreach($files as $file){ // iterate files
            if(!is_dir($file)) {
                unlink($file); // delete file
            }
        }
    }
    /**
     * Will cache a packetAdapter file and return an ID
     * @return string
     * @throws \Exception
     */
    public static function storeAndGet(): string {
        // TODO : replace the session::id call by something else .
        $list = glob(
            join(
                DIRECTORY_SEPARATOR, [
                Kernel::getKernel()->cachePath,
                self::PACKET_ADAPTER_FOLDER,
               "*___" . Session::id() . ".txt"
        ]));
        if(isset($list[0])) {
            $split = explode("/", $list[0]);
            $id = explode("___", $split[count($split) - 1])[0];
            $exploded = explode("\\", $id);
            $id = $exploded[count($exploded) - 1];
        } else {
            $id = self::generateId();
        }
        $filePath = join(DIRECTORY_SEPARATOR, [ dirname(__FILE__) , "..", "..", "..", "..", "cache", self::PACKET_ADAPTER_FOLDER, $id . "___" . Session::id() . ".txt",]);
        $fp = fopen($filePath, "w+");
        fwrite($fp, (serialize($_SERVER)));
        return $id;
    }

    /**
     * Will return a packetAdapter cached file
     * @param string $id
     * @return array
     */
    public static function get(string $id): array {
        $list = glob(
        join(
        DIRECTORY_SEPARATOR, [
            Kernel::getKernel()->cachePath,
            "packet_adapter",
            $id. "___*.txt"
        ]));
        if (isset($list[0])) {
            $filePath = $list[0]; // Assuming there'll only be one match for each day.
            if (file_exists($filePath)) {
                $fp = fopen($filePath, 'r') or die('cant open file');
                return unserialize(fread($fp, filesize($filePath)));
            }
        } else {
            return [];
        }
    }

    /**
     * Will generate a new ID
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public static function  generateId(int $length = 20): string {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $length);
    }
}